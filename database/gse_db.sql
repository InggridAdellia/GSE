--
-- PostgreSQL database dump
--

\restrict 1x9E6WdZ2Cv2INfVnaPuPeMXa1vQ2RClxQcaXKU3fpVKgEHInOtOzdMmdcQ35Rk

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

-- Started on 2026-08-19 14:37:59

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 219 (class 1259 OID 33396)
-- Name: activity_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.activity_log (
    id integer NOT NULL,
    id_user integer,
    username character varying(100),
    role character varying(50),
    ip_address character varying(45) NOT NULL,
    modul character varying(50),
    aksi character varying(100),
    keterangan text,
    status character varying(10) DEFAULT 'success'::character varying NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.activity_log OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 33407)
-- Name: activity_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.activity_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.activity_log_id_seq OWNER TO postgres;

--
-- TOC entry 5198 (class 0 OID 0)
-- Dependencies: 220
-- Name: activity_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.activity_log_id_seq OWNED BY public.activity_log.id;


--
-- TOC entry 221 (class 1259 OID 33408)
-- Name: airlines; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.airlines (
    id_airline integer NOT NULL,
    nama_airline character varying(100) NOT NULL,
    kode_airline character varying(10) NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    status character varying(20) DEFAULT 'Aktif'::character varying NOT NULL
);


ALTER TABLE public.airlines OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 33418)
-- Name: airlines_id_airline_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.airlines_id_airline_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.airlines_id_airline_seq OWNER TO postgres;

--
-- TOC entry 5199 (class 0 OID 0)
-- Dependencies: 222
-- Name: airlines_id_airline_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.airlines_id_airline_seq OWNED BY public.airlines.id_airline;


--
-- TOC entry 223 (class 1259 OID 33419)
-- Name: blocked_ip; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.blocked_ip (
    id integer NOT NULL,
    ip_address character varying(45) NOT NULL,
    alasan text,
    blocked_at timestamp without time zone DEFAULT now() NOT NULL,
    blocked_until timestamp without time zone,
    is_active boolean DEFAULT true NOT NULL
);


ALTER TABLE public.blocked_ip OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 33430)
-- Name: blocked_ip_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.blocked_ip_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.blocked_ip_id_seq OWNER TO postgres;

--
-- TOC entry 5200 (class 0 OID 0)
-- Dependencies: 224
-- Name: blocked_ip_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.blocked_ip_id_seq OWNED BY public.blocked_ip.id;


--
-- TOC entry 225 (class 1259 OID 33431)
-- Name: detail_permohonan_keluar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detail_permohonan_keluar (
    id integer NOT NULL,
    id_permohonan_keluar integer NOT NULL,
    nama_gse character varying(150) NOT NULL,
    manufacture_type character varying(50) NOT NULL,
    no_asset character varying(100),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    sticker_ap character varying(50),
    file_bukti_kerusakan character varying(100),
    file_foto_gse character varying(100),
    alasan_penolakan text,
    verifikasi_oleh character varying(100),
    verifikasi_at timestamp without time zone,
    tahap_saat_ini character varying(20) DEFAULT 'operasi'::character varying,
    status_item character varying(20),
    alasan_penolakan_item text,
    jenis_item character varying(30),
    keterangan text
);


ALTER TABLE public.detail_permohonan_keluar OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 33442)
-- Name: detail_permohonan_keluar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.detail_permohonan_keluar_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.detail_permohonan_keluar_id_seq OWNER TO postgres;

--
-- TOC entry 5201 (class 0 OID 0)
-- Dependencies: 226
-- Name: detail_permohonan_keluar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.detail_permohonan_keluar_id_seq OWNED BY public.detail_permohonan_keluar.id;


--
-- TOC entry 227 (class 1259 OID 33443)
-- Name: detail_permohonan_masuk; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detail_permohonan_masuk (
    id integer CONSTRAINT permohonan_masuk_gse_id_not_null NOT NULL,
    id_permohonan_masuk integer,
    file_ktp character varying(255),
    file_tim character varying(255),
    file_stnk character varying(255),
    file_sim character varying(255),
    created_at timestamp without time zone DEFAULT now() CONSTRAINT permohonan_masuk_gse_created_at_not_null NOT NULL,
    file_bukti_perbaikan character varying(100),
    nama_gse character varying(100),
    manufacture_type character varying(100),
    no_asset character varying(50),
    sticker_ap character varying(50),
    file_foto_gse character varying(100),
    status_item character varying(20) DEFAULT NULL::character varying,
    alasan_penolakan_item text,
    diproses_oleh integer,
    diproses_at timestamp without time zone,
    status_operasi character varying(30) DEFAULT 'Menunggu'::character varying,
    alasan_operasi text,
    operasi_oleh integer,
    operasi_at timestamp without time zone,
    status_equipment character varying(30) DEFAULT 'Menunggu'::character varying,
    alasan_equipment text,
    equipment_oleh integer,
    equipment_at timestamp without time zone,
    status_sales character varying(30) DEFAULT 'Menunggu'::character varying,
    alasan_sales text,
    sales_oleh integer,
    sales_at timestamp without time zone,
    status_security character varying(30) DEFAULT 'Menunggu'::character varying,
    alasan_security text,
    security_oleh integer,
    security_at timestamp without time zone,
    tahap_saat_ini character varying(50),
    verifikasi_oleh integer,
    verifikasi_at timestamp without time zone,
    file_ba_uji_laik character varying(255),
    status_kelayakan character varying(50),
    dimensi_p numeric(8,2),
    dimensi_l numeric(8,2),
    dimensi_luas numeric(10,2),
    masa_mulai date,
    masa_selesai date,
    file_emisi character varying(255),
    dimensi_diisi_pertama_at timestamp without time zone,
    dimensi_edit_count integer DEFAULT 0,
    jenis_nomor_diubah character varying(20) DEFAULT NULL::character varying,
    nomor_baru character varying(100) DEFAULT NULL::character varying,
    file_perubahan_rangka character varying(255) DEFAULT NULL::character varying,
    file_surat_rekomendasi character varying(255),
    file_pass_kendaraan character varying(255),
    nomor_rangka character varying(100),
    nomor_mesin character varying(100),
    file_perubahan_mesin character varying(255),
    file_foto_rangka character varying(255),
    file_foto_mesin character varying(255),
    jenis_unit character varying(20) DEFAULT NULL::character varying,
    jenis_item character varying(30),
    keterangan text
);


ALTER TABLE public.detail_permohonan_masuk OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 33460)
-- Name: gse; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.gse (
    id_gse integer NOT NULL,
    nama_gse character varying(100) NOT NULL,
    status character varying(20) DEFAULT 'Aktif'::character varying NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    id_airline integer,
    manufacture_type character varying(100),
    no_asset character varying(50),
    sticker_ap character varying(50),
    nomor_rangka character varying(100) DEFAULT NULL::character varying,
    nomor_mesin character varying(100) DEFAULT NULL::character varying,
    masa_mulai date,
    masa_selesai date,
    CONSTRAINT gse_status_check CHECK (((status)::text = ANY (ARRAY[('Aktif'::character varying)::text, ('Tidak Aktif'::character varying)::text, ('Proses Perbaruan'::character varying)::text, ('Sedang Diperbaiki'::character varying)::text])))
);


ALTER TABLE public.gse OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 33476)
-- Name: gse_id_gse_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.gse_id_gse_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.gse_id_gse_seq OWNER TO postgres;

--
-- TOC entry 5202 (class 0 OID 0)
-- Dependencies: 229
-- Name: gse_id_gse_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gse_id_gse_seq OWNED BY public.gse.id_gse;


--
-- TOC entry 230 (class 1259 OID 33477)
-- Name: ids_ancaman; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ids_ancaman (
    id integer NOT NULL,
    tipe character varying(50) NOT NULL,
    payload text,
    ip_address character varying(45) NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.ids_ancaman OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 33487)
-- Name: ids_ancaman_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ids_ancaman_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ids_ancaman_id_seq OWNER TO postgres;

--
-- TOC entry 5203 (class 0 OID 0)
-- Dependencies: 231
-- Name: ids_ancaman_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ids_ancaman_id_seq OWNED BY public.ids_ancaman.id;


--
-- TOC entry 232 (class 1259 OID 33488)
-- Name: login_log; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.login_log (
    id integer NOT NULL,
    username character varying(100) NOT NULL,
    ip_address character varying(45) NOT NULL,
    user_agent text,
    status character varying(10) NOT NULL,
    keterangan text,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.login_log OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 33499)
-- Name: login_log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.login_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.login_log_id_seq OWNER TO postgres;

--
-- TOC entry 5204 (class 0 OID 0)
-- Dependencies: 233
-- Name: login_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.login_log_id_seq OWNED BY public.login_log.id;


--
-- TOC entry 234 (class 1259 OID 33500)
-- Name: permohonan_keluar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permohonan_keluar (
    id_permohonan_keluar integer NOT NULL,
    nomor_permohonan character varying(50) NOT NULL,
    tanggal_keluar date NOT NULL,
    status character varying(50) DEFAULT 'Menunggu Verifikasi Operasional'::character varying NOT NULL,
    created_by integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    id_airline integer,
    nama_pemohon character varying(100),
    asal_instansi character varying(150),
    alasan_penolakan text,
    verifikasi_operasi character varying(20),
    verifikasi_operasi_at timestamp without time zone,
    verifikasi_operasi_oleh integer,
    verifikasi_equipment character varying(20),
    verifikasi_equipment_at timestamp without time zone,
    verifikasi_equipment_oleh integer,
    verifikasi_sales character varying(20),
    verifikasi_sales_at timestamp without time zone,
    verifikasi_sales_oleh integer,
    tujuan_keluar character varying(30) DEFAULT 'Perbaikan'::character varying NOT NULL,
    status_perbaikan character varying(20) DEFAULT NULL::character varying,
    jenis_permohonan character varying(20) DEFAULT 'Keluar Baru'::character varying NOT NULL,
    verifikasi_security character varying(20),
    verifikasi_security_at timestamp without time zone,
    verifikasi_security_oleh integer,
    nomor_surat character varying(100),
    jumlah_unit_gse integer,
    file_bukti_permohonan character varying(255),
    keterangan text,
    jumlah_unit_edit_count integer DEFAULT 0,
    jumlah_unit_diedit_pertama_at timestamp without time zone
);


ALTER TABLE public.permohonan_keluar OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 33519)
-- Name: permohonan_keluar_id_permohonan_keluar_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permohonan_keluar_id_permohonan_keluar_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permohonan_keluar_id_permohonan_keluar_seq OWNER TO postgres;

--
-- TOC entry 5205 (class 0 OID 0)
-- Dependencies: 235
-- Name: permohonan_keluar_id_permohonan_keluar_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permohonan_keluar_id_permohonan_keluar_seq OWNED BY public.permohonan_keluar.id_permohonan_keluar;


--
-- TOC entry 236 (class 1259 OID 33520)
-- Name: permohonan_masuk; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permohonan_masuk (
    id_permohonan_masuk integer NOT NULL,
    nomor_permohonan character varying(50) NOT NULL,
    tanggal_masuk date NOT NULL,
    asal_instansi character varying(150),
    keterangan text,
    status character varying(50) DEFAULT 'Menunggu Verifikasi Operasional'::character varying NOT NULL,
    alasan_penolakan text,
    verifikasi_operasi character varying(20),
    verifikasi_operasi_at timestamp without time zone,
    verifikasi_operasi_oleh integer,
    verifikasi_equipment character varying(20),
    verifikasi_equipment_at timestamp without time zone,
    verifikasi_equipment_oleh integer,
    verifikasi_sales character varying(20),
    verifikasi_sales_at timestamp without time zone,
    verifikasi_sales_oleh integer,
    created_by integer,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone DEFAULT now() NOT NULL,
    id_airline integer,
    id_permohonan_keluar integer,
    jenis_permohonan character varying(30) DEFAULT 'Masuk Baru'::character varying,
    jam_masuk time without time zone,
    driver character varying(100),
    file_surat_permohonan character varying(255),
    verifikasi_security character varying(20),
    verifikasi_security_at timestamp without time zone,
    verifikasi_security_oleh integer,
    jumlah_unit_gse integer DEFAULT 0 NOT NULL,
    nomor_surat character varying(100),
    file_bukti_permohonan character varying(255),
    jumlah_unit_edit_count integer DEFAULT 0,
    jumlah_unit_diedit_pertama_at timestamp without time zone
);


ALTER TABLE public.permohonan_masuk OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 33538)
-- Name: permohonan_masuk_gse_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permohonan_masuk_gse_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permohonan_masuk_gse_id_seq OWNER TO postgres;

--
-- TOC entry 5206 (class 0 OID 0)
-- Dependencies: 237
-- Name: permohonan_masuk_gse_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permohonan_masuk_gse_id_seq OWNED BY public.detail_permohonan_masuk.id;


--
-- TOC entry 238 (class 1259 OID 33539)
-- Name: permohonan_masuk_id_permohonan_masuk_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permohonan_masuk_id_permohonan_masuk_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permohonan_masuk_id_permohonan_masuk_seq OWNER TO postgres;

--
-- TOC entry 5207 (class 0 OID 0)
-- Dependencies: 238
-- Name: permohonan_masuk_id_permohonan_masuk_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permohonan_masuk_id_permohonan_masuk_seq OWNED BY public.permohonan_masuk.id_permohonan_masuk;


--
-- TOC entry 239 (class 1259 OID 33540)
-- Name: riwayat_verifikasi_gse; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.riwayat_verifikasi_gse (
    id integer NOT NULL,
    id_detail integer NOT NULL,
    tahap character varying(20) NOT NULL,
    aksi character varying(30) NOT NULL,
    oleh integer,
    catatan text,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.riwayat_verifikasi_gse OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 33551)
-- Name: riwayat_verifikasi_gse_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.riwayat_verifikasi_gse_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.riwayat_verifikasi_gse_id_seq OWNER TO postgres;

--
-- TOC entry 5208 (class 0 OID 0)
-- Dependencies: 240
-- Name: riwayat_verifikasi_gse_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.riwayat_verifikasi_gse_id_seq OWNED BY public.riwayat_verifikasi_gse.id;


--
-- TOC entry 241 (class 1259 OID 33552)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id_user integer NOT NULL,
    username character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    nama character varying(100) NOT NULL,
    role character varying(30) DEFAULT 'unit_operasional'::character varying NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    id_airline integer,
    status character varying(20) DEFAULT 'Aktif'::character varying NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 33565)
-- Name: users_id_user_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_user_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_user_seq OWNER TO postgres;

--
-- TOC entry 5209 (class 0 OID 0)
-- Dependencies: 242
-- Name: users_id_user_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_user_seq OWNED BY public.users.id_user;


--
-- TOC entry 4911 (class 2604 OID 33566)
-- Name: activity_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_log ALTER COLUMN id SET DEFAULT nextval('public.activity_log_id_seq'::regclass);


--
-- TOC entry 4914 (class 2604 OID 33567)
-- Name: airlines id_airline; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.airlines ALTER COLUMN id_airline SET DEFAULT nextval('public.airlines_id_airline_seq'::regclass);


--
-- TOC entry 4917 (class 2604 OID 33568)
-- Name: blocked_ip id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blocked_ip ALTER COLUMN id SET DEFAULT nextval('public.blocked_ip_id_seq'::regclass);


--
-- TOC entry 4920 (class 2604 OID 33569)
-- Name: detail_permohonan_keluar id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_permohonan_keluar ALTER COLUMN id SET DEFAULT nextval('public.detail_permohonan_keluar_id_seq'::regclass);


--
-- TOC entry 4923 (class 2604 OID 33570)
-- Name: detail_permohonan_masuk id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_permohonan_masuk ALTER COLUMN id SET DEFAULT nextval('public.permohonan_masuk_gse_id_seq'::regclass);


--
-- TOC entry 4935 (class 2604 OID 33571)
-- Name: gse id_gse; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gse ALTER COLUMN id_gse SET DEFAULT nextval('public.gse_id_gse_seq'::regclass);


--
-- TOC entry 4941 (class 2604 OID 33572)
-- Name: ids_ancaman id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ids_ancaman ALTER COLUMN id SET DEFAULT nextval('public.ids_ancaman_id_seq'::regclass);


--
-- TOC entry 4943 (class 2604 OID 33573)
-- Name: login_log id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login_log ALTER COLUMN id SET DEFAULT nextval('public.login_log_id_seq'::regclass);


--
-- TOC entry 4945 (class 2604 OID 33574)
-- Name: permohonan_keluar id_permohonan_keluar; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar ALTER COLUMN id_permohonan_keluar SET DEFAULT nextval('public.permohonan_keluar_id_permohonan_keluar_seq'::regclass);


--
-- TOC entry 4953 (class 2604 OID 33575)
-- Name: permohonan_masuk id_permohonan_masuk; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk ALTER COLUMN id_permohonan_masuk SET DEFAULT nextval('public.permohonan_masuk_id_permohonan_masuk_seq'::regclass);


--
-- TOC entry 4960 (class 2604 OID 33576)
-- Name: riwayat_verifikasi_gse id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.riwayat_verifikasi_gse ALTER COLUMN id SET DEFAULT nextval('public.riwayat_verifikasi_gse_id_seq'::regclass);


--
-- TOC entry 4962 (class 2604 OID 33577)
-- Name: users id_user; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id_user SET DEFAULT nextval('public.users_id_user_seq'::regclass);


--
-- TOC entry 5169 (class 0 OID 33396)
-- Dependencies: 219
-- Data for Name: activity_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.activity_log (id, id_user, username, role, ip_address, modul, aksi, keterangan, status, created_at) FROM stdin;
1	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PMP/000002	success	2026-07-10 14:46:37
2	6	gh_lion	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 4	success	2026-07-10 15:20:35
3	6	gh_lion	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 4	success	2026-07-10 15:21:31
4	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 45	success	2026-07-10 15:23:37
5	6	gh_lion	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 4	success	2026-07-10 15:29:36
6	6	gh_lion	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 4	success	2026-07-10 15:30:37
7	6	gh_lion	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 4	success	2026-07-10 15:31:52
8	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Ground Power Unit" ditambahkan ke permohonan ID 5 (1/1)	success	2026-07-10 15:34:03
9	6	gh_lion	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 5	success	2026-07-10 15:48:17
10	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PMB/000003	success	2026-07-13 08:58:29
11	5	gh_garuda	ground_handling	::1	Permohonan Keluar	hapus_permohonan	Permohonan ID 8 dihapus	success	2026-07-13 08:58:38
12	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PMP/000003	success	2026-07-13 08:59:04
13	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 9 (1/3)	success	2026-07-13 09:00:36
14	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PMP/000003	success	2026-07-13 09:26:09
15	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PMB/000003	success	2026-07-13 09:32:07
16	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 11 (1/2)	success	2026-07-13 10:02:06
17	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PMP/000003	success	2026-07-13 10:03:08
18	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Water Service Truck" ditambahkan ke permohonan ID 12 (1/1)	success	2026-07-13 10:05:51
19	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000004	success	2026-07-13 10:15:13
20	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 46 (1/3)	success	2026-07-13 10:15:30
21	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "BTT Unit 10" ditambahkan ke permohonan ID 46 (2/3)	success	2026-07-13 10:16:47
22	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "HLL Unit 015" ditambahkan ke permohonan ID 46 (3/3)	success	2026-07-13 10:17:15
23	5	gh_garuda	ground_handling	::1	Permohonan Masuk	ajukan_ulang	Permohonan ID 46 diajukan ulang	success	2026-07-13 12:08:42
24	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 46 (1/3)	success	2026-07-13 14:16:04
25	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Aircraft Towing Tractor" ditambahkan ke permohonan ID 46 (2/3)	success	2026-07-13 14:19:11
26	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 46 (3/3)	success	2026-07-13 14:21:16
27	1	admin	admin	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 5	success	2026-07-13 14:44:02
28	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000002	success	2026-07-13 16:34:31
29	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000005	success	2026-07-13 16:39:54
30	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 46	success	2026-07-15 23:57:53
31	1	admin	admin	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 46	success	2026-07-15 23:58:02
32	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 46	success	2026-07-15 23:58:06
33	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 46	success	2026-07-16 00:01:31
34	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000006	success	2026-07-16 00:11:21
35	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Belt Conveyor Loader" ditambahkan ke permohonan ID 49 (1/2)	success	2026-07-16 00:13:18
36	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 49 (2/2)	success	2026-07-16 00:16:30
37	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 49	success	2026-07-16 00:59:33
38	6	gh_lion	ground_handling	::1	GSE	ubah_status_gse	GSE #7 (Belt Conveyor Loader) diubah ke status Tidak Aktif	success	2026-07-16 01:05:15
39	6	gh_lion	ground_handling	::1	GSE	ubah_status_gse	GSE #7 (Belt Conveyor Loader) diubah ke status Aktif	success	2026-07-16 01:05:17
40	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000007	success	2026-07-16 09:24:51
41	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 50 (1/1)	success	2026-07-16 09:25:15
42	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000008	success	2026-07-16 09:26:18
43	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "PALLET DOLLY" ditambahkan ke permohonan ID 51 (1/3)	success	2026-07-16 09:27:39
44	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Belt Conveyor Loader" ditambahkan ke permohonan ID 51 (2/3)	success	2026-07-16 09:28:29
45	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Aircraft Towing Tractor" ditambahkan ke permohonan ID 51 (3/3)	success	2026-07-16 09:29:42
46	6	gh_lion	ground_handling	::1	Permohonan Masuk	ajukan_ulang	Permohonan ID 51 diajukan ulang	success	2026-07-16 09:35:00
47	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 51 (3/3)	success	2026-07-16 09:35:45
48	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000001	success	2026-07-16 10:01:36
49	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 13 (1/2)	success	2026-07-16 10:04:38
50	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000002	success	2026-07-16 10:06:22
51	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Belt Conveyor Loader" ditambahkan ke permohonan ID 14 (1/2)	success	2026-07-16 10:06:30
63	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000009	success	2026-07-17 09:31:47
52	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 14 (2/2)	success	2026-07-16 10:07:40
53	6	gh_lion	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 14	success	2026-07-16 10:08:20
54	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000003	success	2026-07-16 10:09:15
55	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PKP/000001	success	2026-07-16 10:11:10
56	6	gh_lion	ground_handling	::1	Permohonan Keluar	hapus_permohonan	Permohonan ID 15 dihapus	success	2026-07-16 10:11:15
57	6	gh_lion	ground_handling	::1	Permohonan Masuk	ajukan_ulang	Permohonan ID 51 diajukan ulang	success	2026-07-16 10:34:58
58	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 51 (3/3)	success	2026-07-16 12:08:41
64	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 53 (1/2)	success	2026-07-17 09:32:38
65	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 53 (2/2)	success	2026-07-17 09:34:26
99	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000001	success	2026-07-20 09:14:06
100	6	gh_lion	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 1 dihapus	success	2026-07-20 09:18:54
101	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000001	success	2026-07-20 09:19:25
102	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 2 (1/2)	success	2026-07-20 09:28:11
103	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Aircraft Towing Tractor" ditambahkan ke permohonan ID 2 (2/2)	success	2026-07-20 09:29:29
104	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000001	success	2026-07-20 09:46:04
105	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 3 (1/2)	success	2026-07-20 09:46:20
106	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 3 (2/2)	success	2026-07-20 09:47:14
107	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 4 disetujui di tahap operasi	success	2026-07-20 10:07:51
108	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 3 disetujui di tahap operasi	success	2026-07-20 10:08:22
109	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 3 diupload	success	2026-07-20 10:09:08
110	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 4 diupload	success	2026-07-20 10:31:19
111	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:10:25
112	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:10:40
113	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:12:51
114	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:25:24
115	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:30:00
116	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:30:33
117	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:30:38
118	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:30:43
119	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 3	success	2026-07-20 11:30:51
120	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 3 disetujui di tahap sales	success	2026-07-20 11:31:25
121	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 4 disetujui di tahap sales	success	2026-07-20 11:31:27
122	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 3 disetujui di tahap security	success	2026-07-20 11:31:28
123	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 4 disetujui di tahap security	success	2026-07-20 11:31:30
124	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 3	success	2026-07-20 11:31:39
125	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 3	success	2026-07-20 11:34:58
126	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 3	success	2026-07-20 11:35:08
127	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 3	success	2026-07-20 11:38:51
128	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 3	success	2026-07-20 11:43:24
129	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000001	success	2026-07-20 13:36:03
130	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 4 (1/1)	success	2026-07-20 13:36:53
131	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 5 disetujui di tahap operasi	success	2026-07-20 13:38:56
132	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: Lengkapi kembali	success	2026-07-20 13:54:56
133	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 4 (1/1)	success	2026-07-20 14:12:35
134	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 6 disetujui di tahap operasi	success	2026-07-20 14:12:59
135	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 6 ditolak: Perbaiki gambar bukti kerusakan	success	2026-07-20 14:13:26
136	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000001	success	2026-07-20 14:28:51
137	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 5 (1/1)	success	2026-07-20 14:29:50
138	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 7 disetujui di tahap operasi	success	2026-07-20 14:30:21
139	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 7 ditolak: Lengkapi	success	2026-07-20 14:30:32
140	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000002	success	2026-07-20 14:52:23
141	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000002	success	2026-07-20 15:26:01
142	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Aircraft Towing Tractor" ditambahkan ke permohonan ID 7 (1/1)	success	2026-07-20 15:27:02
143	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 8 disetujui di tahap operasi	success	2026-07-20 15:40:11
144	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 8 ditolak: Ajukan kembali	success	2026-07-20 15:40:22
145	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar' (ID: 7) pada permohonan PMP/000001	success	2026-07-20 16:05:05
146	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 7 ditolak: Lengkapi	success	2026-07-20 16:05:44
147	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar' (ID: 7) pada permohonan PMP/000001	success	2026-07-20 16:09:26
148	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 7 disetujui di tahap operasi	success	2026-07-20 16:09:45
149	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 7 ditolak: Foto bukti perbaikan buram	success	2026-07-20 16:10:02
150	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 3	success	2026-07-20 16:21:48
151	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000003	success	2026-07-20 16:35:37
152	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 8 (1/2)	success	2026-07-20 16:35:59
153	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 8 (2/2)	success	2026-07-20 16:36:47
154	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 9 disetujui di tahap operasi	success	2026-07-20 16:37:09
155	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 10 disetujui di tahap operasi	success	2026-07-20 16:37:11
156	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 9 diupload	success	2026-07-20 16:37:30
157	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 10 diupload	success	2026-07-20 16:37:40
158	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 8	success	2026-07-20 16:37:55
159	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 9 disetujui di tahap sales	success	2026-07-20 16:38:10
160	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 10 disetujui di tahap sales	success	2026-07-20 16:38:11
161	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 9 disetujui di tahap security	success	2026-07-20 16:38:12
162	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 10 disetujui di tahap security	success	2026-07-20 16:38:14
163	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 8	success	2026-07-20 16:38:23
164	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000004	success	2026-07-20 16:43:52
165	6	gh_lion	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 9 dihapus	success	2026-07-20 16:44:02
166	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000004	success	2026-07-20 16:44:23
167	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 10 (1/1)	success	2026-07-20 16:44:41
168	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 11 disetujui di tahap operasi	success	2026-07-20 16:44:50
169	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 11 diupload	success	2026-07-20 16:45:00
170	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 10	success	2026-07-21 08:57:51
171	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000005	success	2026-07-21 09:04:24
172	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000003	success	2026-07-21 09:05:05
173	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 8 diupload	success	2026-07-21 09:39:51
174	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000001	success	2026-07-21 11:43:43
175	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000002	success	2026-07-21 12:11:51
176	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 18 (1/1)	success	2026-07-21 12:12:15
177	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 12 disetujui di tahap operasi	success	2026-07-21 12:12:34
178	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 11 ditolak: Lengkapi kembali	success	2026-07-21 14:16:01
179	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 7 ditolak: Lengkapi kembali	success	2026-07-21 14:16:17
180	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar' (ID: 7) pada permohonan PMP/000001	success	2026-07-21 14:21:36
181	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 8 ditolak: Gambar SIM Driver buram	success	2026-07-21 14:34:08
182	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Aircraft Towing Tractor' (ID: 8) pada permohonan PMB/000002	success	2026-07-21 14:44:12
183	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 8 disetujui di tahap operasi	success	2026-07-21 14:44:30
184	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 8 diupload	success	2026-07-21 14:44:39
185	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000003	success	2026-07-21 14:59:55
186	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PKP/000001	success	2026-07-21 15:00:31
187	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 19 (1/1)	success	2026-07-21 15:00:50
188	1	admin	admin	::1	Approval	tolak_item_keluar	Unit GSE keluar ID 13 ditolak: Lengkapi	success	2026-07-21 15:01:42
189	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 7	success	2026-07-21 15:35:59
190	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 8	success	2026-07-21 15:36:02
191	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 8	success	2026-07-21 15:36:04
192	6	gh_lion	ground_handling	::1	Permohonan Keluar	edit_unit	Unit "Towing Bar" diperbaiki pada permohonan ID 19	success	2026-07-21 16:05:01
193	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000001	success	2026-07-21 16:07:23
194	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 21 (1/1)	success	2026-07-21 16:07:43
195	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PKP/000001	success	2026-07-21 16:08:25
196	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 14 disetujui di tahap operasi	success	2026-07-21 16:08:53
197	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 14 disetujui di tahap equipment	success	2026-07-21 16:08:58
198	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 14 disetujui di tahap sales	success	2026-07-21 16:09:01
199	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 14 disetujui di tahap security	success	2026-07-21 16:09:04
200	6	gh_lion	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 21	success	2026-07-21 16:09:21
201	1	admin	admin	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 21	success	2026-07-21 16:18:51
202	1	admin	admin	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 21	success	2026-07-21 16:19:27
203	1	admin	admin	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 21	success	2026-07-21 16:27:23
204	1	admin	admin	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 21	success	2026-07-21 16:30:11
205	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 22 (1/2)	success	2026-07-21 16:31:27
206	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 7 disetujui di tahap operasi	success	2026-07-21 16:36:44
207	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 7 diupload	success	2026-07-21 16:36:54
208	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000006	success	2026-07-21 16:39:58
209	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 8	success	2026-07-21 16:40:02
210	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 13 (1/2)	success	2026-07-21 16:41:31
211	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000002	success	2026-07-21 16:44:00
212	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 23 (1/1)	success	2026-07-21 16:45:02
213	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000007	success	2026-07-22 14:48:11
214	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 14 (1/2)	success	2026-07-22 14:49:32
215	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 14 (2/2)	success	2026-07-22 14:54:15
216	2	unit_operasi	unit_operasi	::1	Approval	tolak_item	Unit GSE ID 14 ditolak: Masa berlaku TIM sudah habis	success	2026-07-22 14:58:17
217	5	gh_garuda	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 14) pada permohonan PMB/000007	success	2026-07-22 14:59:18
218	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 14 disetujui di tahap operasi	success	2026-07-22 14:59:37
219	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 13 disetujui di tahap operasi	success	2026-07-22 14:59:41
220	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 14 diupload	success	2026-07-22 15:01:07
221	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 13 diupload	success	2026-07-22 15:03:18
222	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 14	success	2026-07-22 15:03:35
223	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 14	success	2026-07-22 15:04:01
224	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 14	success	2026-07-22 15:04:15
225	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 13 disetujui di tahap security	success	2026-07-22 15:13:16
226	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 14 disetujui di tahap security	success	2026-07-22 15:15:20
227	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 14	success	2026-07-22 15:15:27
228	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 14	success	2026-07-22 15:15:31
229	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000003	success	2026-07-22 15:30:59
230	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 24 (1/1)	success	2026-07-22 15:31:27
231	2	unit_operasi	unit_operasi	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 17 disetujui di tahap operasi	success	2026-07-22 15:32:27
232	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PKP/000002	success	2026-07-22 15:39:18
233	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 25 (1/1)	success	2026-07-22 15:40:10
234	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000004	success	2026-07-22 15:42:15
235	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 14	success	2026-07-22 15:47:29
268	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 14	success	2026-07-22 16:13:01
269	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 14	success	2026-07-22 16:13:11
270	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 8 ditolak: Lengkapi	success	2026-07-23 09:44:46
271	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Aircraft Towing Tractor' (ID: 8) pada permohonan PMB/000002	success	2026-07-23 10:03:36
272	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Pallet Dolly' (ID: 11) pada permohonan PMB/000004	success	2026-07-23 13:51:40
273	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 7 disetujui di tahap security	success	2026-07-23 13:52:23
274	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 11 disetujui di tahap operasi	success	2026-07-23 13:52:57
275	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 11 diupload	success	2026-07-23 13:53:20
276	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000008	success	2026-07-23 14:53:42
277	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 16 (1/2)	success	2026-07-23 14:54:01
278	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 16 (2/2)	success	2026-07-23 14:55:02
279	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 15 disetujui di tahap operasi	success	2026-07-23 14:55:59
280	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 15 diupload	success	2026-07-23 14:56:14
281	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 15 disetujui di tahap security	success	2026-07-23 14:56:57
282	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 16 disetujui di tahap operasi	success	2026-07-23 14:57:41
283	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 16 diupload	success	2026-07-23 14:57:53
284	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000008	success	2026-07-23 15:15:40
339	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 2 permohonan ID 1	success	2026-07-24 10:30:46
285	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 17 (1/2)	success	2026-07-23 15:15:58
286	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truck" ditambahkan ke permohonan ID 17 (2/2)	success	2026-07-23 15:16:48
287	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 17 disetujui di tahap operasi	success	2026-07-23 15:17:22
288	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 17 diupload	success	2026-07-23 15:17:32
289	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 18 disetujui di tahap operasi	success	2026-07-23 15:37:32
290	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 18 diupload	success	2026-07-23 15:39:10
291	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 17 disetujui di tahap security	success	2026-07-23 15:52:30
292	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 18 disetujui di tahap security	success	2026-07-23 15:52:41
293	1	admin	admin	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 17	success	2026-07-23 15:52:48
294	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 17	success	2026-07-23 15:52:51
295	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000009	success	2026-07-23 16:21:18
296	8	gh_citilink	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 18 dihapus	success	2026-07-23 16:21:28
297	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000005	success	2026-07-23 16:21:46
298	8	gh_citilink	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PKP/000003	success	2026-07-23 16:31:19
299	8	gh_citilink	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000004	success	2026-07-24 09:02:07
300	8	gh_citilink	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 27 (1/1)	success	2026-07-24 09:03:20
301	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 17	success	2026-07-24 09:03:48
302	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 17	success	2026-07-24 09:04:08
303	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 17	success	2026-07-24 09:06:40
304	1	admin	admin	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 17	success	2026-07-24 09:09:42
305	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000001	success	2026-07-24 09:14:15
306	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "TOWING BAR" ditambahkan ke permohonan ID 1 (1/2)	success	2026-07-24 09:14:37
307	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "TRUCK HYDRANT DESPENSER" ditambahkan ke permohonan ID 1 (2/2)	success	2026-07-24 09:15:43
308	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 1 disetujui di tahap operasi	success	2026-07-24 09:16:00
309	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 1 diupload	success	2026-07-24 09:16:14
310	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 2 disetujui di tahap operasi	success	2026-07-24 09:16:16
311	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 2 diupload	success	2026-07-24 09:16:24
312	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 1 disetujui di tahap security	success	2026-07-24 09:40:25
313	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 09:54:20
314	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 09:54:28
315	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 09:55:34
316	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 09:56:16
317	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:00:43
318	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:01:09
319	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:02:32
320	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:02:42
321	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 10:04:13
322	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 10:06:04
323	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:06:10
324	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 2 ditolak: Foto sim blur	success	2026-07-24 10:06:36
325	8	gh_citilink	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'TRUCK HYDRANT DESPENSER' (ID: 2) pada permohonan PMB/000001	success	2026-07-24 10:11:28
326	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 2 disetujui di tahap operasi	success	2026-07-24 10:11:36
327	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 2 diupload	success	2026-07-24 10:12:21
328	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 2 disetujui di tahap security	success	2026-07-24 10:13:23
329	11	unit_security	unit_security	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 2 permohonan ID 1	success	2026-07-24 10:13:33
330	11	unit_security	unit_security	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:13:54
331	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:15:44
332	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:22:01
333	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:22:55
334	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:23:26
335	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:24:06
336	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:24:27
337	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:25:27
338	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 2 permohonan ID 1	success	2026-07-24 10:25:31
340	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 2 permohonan ID 1	success	2026-07-24 10:30:51
341	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 2 permohonan ID 1	success	2026-07-24 10:31:15
342	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-07-24 10:32:38
343	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 1	success	2026-07-24 10:32:42
344	8	gh_citilink	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "TOWING BAR" ditambahkan ke permohonan ID 27 (1/1)	success	2026-07-24 10:34:43
345	8	gh_citilink	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000001	success	2026-07-24 10:36:22
346	8	gh_citilink	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "TOWING BAR" ditambahkan ke permohonan ID 1 (1/1)	success	2026-07-24 10:36:38
347	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui di tahap operasi	success	2026-07-24 10:37:47
348	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui di tahap sales	success	2026-07-24 10:43:48
349	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui di tahap security	success	2026-07-24 10:43:52
350	1	admin	admin	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-07-24 10:43:59
351	1	admin	admin	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-07-24 10:45:12
352	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000001	success	2026-07-24 10:50:01
353	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 1 (1/2)	success	2026-07-24 10:50:32
354	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 1 disetujui di tahap operasi	success	2026-07-24 10:50:38
355	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 1 diupload	success	2026-07-24 10:51:05
356	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 1 disetujui di tahap security	success	2026-07-24 10:51:50
357	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 10:52:09
358	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:52:15
359	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 10:55:36
360	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 10:55:39
361	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 1 (2/2)	success	2026-07-24 10:56:40
362	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 2 disetujui di tahap operasi	success	2026-07-24 11:02:28
363	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 2 diupload	success	2026-07-24 11:02:37
364	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 2 disetujui di tahap security	success	2026-07-24 11:03:06
365	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 2 permohonan ID 1	success	2026-07-24 11:03:20
366	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 2 permohonan ID 1	success	2026-07-24 11:03:43
367	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 11:12:42
368	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 11:19:17
369	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 11:21:32
370	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 11:27:39
371	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 11:27:39
372	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 11:50:03
373	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 11:52:21
374	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 11:58:01
375	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 11:58:06
376	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 2 permohonan ID 1	success	2026-07-24 12:00:43
377	2	unit_operasi	unit_operasi	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 13:51:55
378	2	unit_operasi	unit_operasi	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 13:52:51
379	2	unit_operasi	unit_operasi	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 14:00:17
380	2	unit_operasi	unit_operasi	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 14:03:03
381	2	unit_operasi	unit_operasi	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 14:03:04
382	2	unit_operasi	unit_operasi	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 14:03:11
383	2	unit_operasi	unit_operasi	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 14:11:57
384	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-07-24 14:14:44
385	8	gh_citilink	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000001	success	2026-07-24 14:29:06
386	8	gh_citilink	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 1 (1/1)	success	2026-07-24 14:30:09
387	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000001	success	2026-07-24 14:30:53
388	11	unit_security	unit_security	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-24 15:27:39
389	8	gh_citilink	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PKP/000001	success	2026-07-27 08:42:37
390	8	gh_citilink	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 2 (1/1)	success	2026-07-27 08:43:07
391	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 2 disetujui di tahap operasi	success	2026-07-27 08:43:49
392	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 2 disetujui di tahap sales	success	2026-07-27 08:43:54
393	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 2 disetujui di tahap security	success	2026-07-27 08:43:58
394	8	gh_citilink	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-07-27 08:44:07
395	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 08:55:20
396	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:29:40
397	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:31:07
398	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:31:12
399	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:31:37
400	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:35:21
401	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:35:26
402	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:35:45
403	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:36:08
404	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:36:23
405	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:38:18
406	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:39:57
407	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:40:36
408	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 1	success	2026-07-27 09:40:48
409	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 1	success	2026-07-27 09:41:14
410	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:41:19
411	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:46:37
412	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:46:38
413	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:46:41
414	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:48:08
415	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:48:27
416	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 09:51:19
417	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000002	success	2026-07-27 09:58:27
418	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Air Traffic Towing" ditambahkan ke permohonan ID 3 (1/1)	success	2026-07-27 10:24:37
419	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 3 disetujui di tahap operasi	success	2026-07-27 10:51:46
420	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 3 diupload	success	2026-07-27 10:51:58
421	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 3 disetujui di tahap security	success	2026-07-27 13:36:12
422	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 3 permohonan ID 3	success	2026-07-27 13:40:21
423	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 3 permohonan ID 3	success	2026-07-27 13:40:29
424	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 3 permohonan ID 3	success	2026-07-27 14:01:50
425	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 14:02:05
426	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 2 permohonan ID 1	success	2026-07-27 14:02:08
427	8	gh_citilink	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000002	success	2026-07-27 14:11:50
428	8	gh_citilink	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Air Traffic Towing" ditambahkan ke permohonan ID 3 (1/1)	success	2026-07-27 14:12:17
429	8	gh_citilink	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PKP/000002	success	2026-07-27 14:13:02
430	8	gh_citilink	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Air Traffic Towing" ditambahkan ke permohonan ID 4 (1/1)	success	2026-07-27 14:13:51
431	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 4 disetujui di tahap operasi	success	2026-07-27 14:14:39
432	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 4 disetujui di tahap sales	success	2026-07-27 14:14:43
433	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 4 disetujui di tahap security	success	2026-07-27 14:14:48
434	8	gh_citilink	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 4	success	2026-07-27 14:14:55
435	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Air Traffic Towing" ditambahkan ke permohonan ID 2 (1/1)	success	2026-07-27 15:13:52
436	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 4 disetujui di tahap operasi	success	2026-07-27 15:14:37
437	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 4 diupload	success	2026-07-27 15:14:46
438	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 4 disetujui di tahap security	success	2026-07-27 15:15:15
439	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 2	success	2026-07-27 15:15:54
440	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 4 permohonan ID 2	success	2026-07-27 15:46:57
441	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 2	success	2026-07-27 15:47:00
442	8	gh_citilink	ground_handling	::1	GSE	ubah_status_gse	GSE #3 (Air Traffic Towing) diubah ke status Aktif	success	2026-07-27 15:50:59
443	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 1	success	2026-07-27 15:57:16
444	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 15:58:42
445	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 15:58:48
446	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 15:58:55
447	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 16:02:27
448	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-07-27 16:05:46
449	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 2 permohonan ID 1	success	2026-07-27 16:07:15
450	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 3 permohonan ID 3	success	2026-07-27 16:07:51
451	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 4 permohonan ID 2	success	2026-07-27 16:08:20
452	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 1 unit GSE	success	2026-07-27 16:32:42
453	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 3 unit GSE	success	2026-07-27 16:33:44
454	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 3 unit GSE	success	2026-07-27 16:34:28
455	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 3 unit GSE	success	2026-07-27 16:35:20
456	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 3 unit GSE	success	2026-07-27 16:36:16
457	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 1 unit GSE	success	2026-07-27 16:36:22
458	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 3 permohonan ID 3	success	2026-07-27 16:36:46
459	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 2	success	2026-07-27 16:48:09
460	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 2 unit GSE	success	2026-07-28 08:55:32
461	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 2 unit GSE	success	2026-07-28 08:56:07
462	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000002	success	2026-07-28 09:14:30
463	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 4 (1/1)	success	2026-07-28 09:46:49
464	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: Lengkapi Berkas Perubahan	success	2026-07-28 09:49:10
465	1	admin	admin	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 1 unit GSE	success	2026-07-28 10:24:41
466	8	gh_citilink	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 5) pada permohonan PMP/000002	success	2026-07-28 10:38:47
467	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: Lengkapi	success	2026-07-28 10:39:31
468	8	gh_citilink	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 5) pada permohonan PMP/000002	success	2026-07-28 11:00:29
469	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: .	success	2026-07-28 11:04:39
470	8	gh_citilink	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 5) pada permohonan PMP/000002	success	2026-07-28 11:05:10
471	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: .	success	2026-07-28 11:08:28
472	8	gh_citilink	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 5) pada permohonan PMP/000002	success	2026-07-28 11:09:07
473	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: .	success	2026-07-28 11:15:05
474	8	gh_citilink	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 5) pada permohonan PMP/000002	success	2026-07-28 11:16:20
475	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: .	success	2026-07-28 11:22:33
476	8	gh_citilink	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 5) pada permohonan PMP/000002	success	2026-07-28 11:22:50
477	1	admin	admin	::1	Users	tambah_user	User baru: Non-Airline (role: ground_handling)	success	2026-07-28 11:54:26
478	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 3 permohonan ID 3	success	2026-07-28 13:36:13
479	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 2	success	2026-07-28 13:52:26
480	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 3 permohonan ID 3	success	2026-07-28 13:55:54
481	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 2	success	2026-07-28 13:56:18
482	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 4 permohonan ID 2	success	2026-07-28 14:00:51
483	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-07-28 14:02:58
484	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_perbaikan	Nomor: PMP/000003	success	2026-07-28 14:16:25
485	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 5 (1/1)	success	2026-07-28 14:17:04
486	8	gh_citilink	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 4 permohonan ID 2	success	2026-07-28 14:23:52
487	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000003	success	2026-07-28 14:32:36
488	8	gh_citilink	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 6 (1/1)	success	2026-07-28 14:37:11
489	8	gh_citilink	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 6 dihapus	success	2026-07-28 14:46:40
490	8	gh_citilink	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000003	success	2026-07-28 14:46:55
491	1	admin	admin	::1	Users	tambah_user	User baru: GH_PelitaAir (role: ground_handling)	success	2026-07-29 05:44:50
492	1	admin	admin	::1	Users	edit_user	User "gh_pelitaair" (ID 14) diperbarui	success	2026-07-29 05:45:18
493	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000004	success	2026-07-29 05:45:53
494	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Ground Power Unit 01" ditambahkan ke permohonan ID 8 (1/2)	success	2026-07-29 05:46:54
495	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 8 (2/2)	success	2026-07-29 05:51:20
496	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 9 disetujui di tahap operasi	success	2026-07-29 05:52:00
497	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 10 disetujui di tahap operasi	success	2026-07-29 05:52:04
498	3	unit_equipment	unit_equipment	::1	Approval	tolak_item	Unit GSE ID 9 ditolak: Foto blur	success	2026-07-29 05:52:45
499	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 10 diupload	success	2026-07-29 05:52:52
500	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Ground Power Unit 01' (ID: 9) pada permohonan PMB/000004	success	2026-07-29 05:59:06
501	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 9 disetujui di tahap operasi	success	2026-07-29 05:59:53
502	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 9 diupload	success	2026-07-29 06:00:40
503	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 9 disetujui di tahap security	success	2026-07-29 06:15:06
504	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 9 permohonan ID 8	success	2026-07-29 06:16:02
505	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 9 permohonan ID 8	success	2026-07-29 06:16:22
506	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 10 disetujui di tahap security	success	2026-07-29 09:19:06
507	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 10 permohonan ID 8	success	2026-07-29 09:19:19
508	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 10 permohonan ID 8	success	2026-07-29 09:19:22
509	14	gh_pelitaair	ground_handling	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 1 unit GSE	success	2026-07-29 09:32:23
510	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000003 untuk 1 unit GSE	success	2026-07-29 09:35:25
511	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 10 dihapus	success	2026-07-29 09:36:17
512	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 11 dihapus	success	2026-07-29 09:36:33
513	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 9 dihapus	success	2026-07-29 09:36:38
514	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000001 untuk 1 unit GSE	success	2026-07-29 09:36:48
515	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 10 permohonan ID 8	success	2026-07-29 09:42:55
516	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000005	success	2026-07-29 09:45:16
517	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 13 (1/1)	success	2026-07-29 09:45:45
518	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 15 ditolak: Perbaiki	success	2026-07-29 09:46:27
519	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 15) pada permohonan PMB/000005	success	2026-07-29 09:46:39
520	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 15 ditolak: lengkapi	success	2026-07-29 09:46:59
521	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 15) pada permohonan PMB/000005	success	2026-07-29 09:48:15
522	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 15 disetujui di tahap operasi	success	2026-07-29 09:48:36
523	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 12 dihapus	success	2026-07-29 10:04:34
524	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000001 untuk 1 unit GSE	success	2026-07-29 10:04:41
525	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 14 dihapus	success	2026-07-29 10:17:54
526	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000001 untuk 1 unit GSE	success	2026-07-29 10:18:05
527	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000006	success	2026-07-29 10:19:08
528	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar 01" ditambahkan ke permohonan ID 16 (1/1)	success	2026-07-29 10:20:34
529	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 18 disetujui di tahap operasi	success	2026-07-29 10:20:42
530	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 18 diupload	success	2026-07-29 10:20:51
531	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 18 disetujui di tahap security	success	2026-07-29 10:21:23
532	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 18 permohonan ID 16	success	2026-07-29 10:21:35
533	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 18 permohonan ID 16	success	2026-07-29 10:21:38
534	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000002 untuk 1 unit GSE	success	2026-07-29 10:21:49
535	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar 01' (ID: 19) pada permohonan PMK/000002	success	2026-07-29 10:23:26
536	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 19 disetujui di tahap operasi	success	2026-07-29 10:23:34
537	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 19 diupload	success	2026-07-29 10:23:41
538	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 19 disetujui di tahap security	success	2026-07-29 10:24:59
539	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 18 permohonan ID 16	success	2026-07-29 10:25:51
540	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 19 permohonan ID 17	success	2026-07-29 10:26:14
541	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 19 permohonan ID 17	success	2026-07-29 10:26:19
542	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 18 permohonan ID 16	success	2026-07-29 13:45:59
543	14	gh_pelitaair	ground_handling	::1	GSE	cetak_semua_stiker	Cetak stiker massal untuk 2 unit GSE	success	2026-07-29 13:47:26
544	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000003 untuk 1 unit GSE	success	2026-07-29 13:48:27
545	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar 01' (ID: 20) pada permohonan PMK/000003	success	2026-07-29 13:50:46
546	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000007	success	2026-07-29 14:07:26
547	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 19 (1/1)	success	2026-07-29 14:07:48
548	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 21 disetujui di tahap operasi	success	2026-07-29 14:08:02
549	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 21 diupload	success	2026-07-29 14:08:21
550	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 21 disetujui di tahap security	success	2026-07-29 14:12:04
551	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 21 permohonan ID 19	success	2026-07-29 14:12:27
552	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 20 ditolak: .	success	2026-07-29 15:21:10
553	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 15 ditolak: .	success	2026-07-29 15:22:01
554	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 15) pada permohonan PMB/000005	success	2026-07-29 15:22:52
555	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 15 disetujui di tahap operasi	success	2026-07-29 15:23:03
556	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 15 diupload	success	2026-07-29 15:23:12
557	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 8 disetujui di tahap operasi	success	2026-07-30 14:19:49
558	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 8 diupload	success	2026-07-30 14:20:00
559	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000004 untuk 1 unit GSE	success	2026-07-31 09:09:05
560	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Pallet Dolly' (ID: 22) pada permohonan PMK/000004	success	2026-07-31 09:19:48
561	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 22 disetujui di tahap operasi	success	2026-07-31 09:21:04
562	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 22 diupload	success	2026-07-31 09:21:15
563	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 22 ditolak: ,\r\n	success	2026-07-31 09:26:27
564	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Pallet Dolly' (ID: 22) pada permohonan PMK/000004	success	2026-07-31 09:26:39
565	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 22 disetujui di tahap operasi	success	2026-07-31 09:26:46
566	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 22 diupload	success	2026-07-31 09:26:57
567	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000008	success	2026-07-31 09:37:13
568	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 21 (1/2)	success	2026-07-31 09:37:31
569	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Ground Power Unit" ditambahkan ke permohonan ID 21 (2/2)	success	2026-07-31 09:38:24
570	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 23 disetujui di tahap operasi	success	2026-07-31 09:38:40
571	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 23 diupload	success	2026-07-31 09:38:48
572	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 23 disetujui di tahap security	success	2026-07-31 09:39:25
573	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 23 permohonan ID 21	success	2026-07-31 09:40:54
574	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 23 permohonan ID 21	success	2026-07-31 09:40:58
575	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000005 untuk 1 unit GSE	success	2026-07-31 09:41:12
576	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 22 disetujui di tahap security	success	2026-07-31 09:45:09
577	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 22 permohonan ID 20	success	2026-07-31 10:01:59
578	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 22 permohonan ID 20	success	2026-07-31 10:02:02
579	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 22 permohonan ID 20	success	2026-07-31 10:02:05
580	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 18 permohonan ID 16	success	2026-07-31 10:15:29
581	4	unit_sales	unit_sales	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 21 permohonan ID 19	success	2026-07-31 10:20:15
582	4	unit_sales	unit_sales	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 21 permohonan ID 19	success	2026-07-31 10:22:59
583	4	unit_sales	unit_sales	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 21 permohonan ID 19	success	2026-07-31 10:23:18
584	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	tambah_perbaikan	Nomor: PKP/000003	success	2026-07-31 10:30:35
585	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000009	success	2026-08-04 06:36:53
586	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bari baru" ditambahkan ke permohonan ID 23 (1/2)	success	2026-08-04 06:37:31
587	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 26 ditolak: ,	success	2026-08-04 06:53:25
588	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar baru' (ID: 26) pada permohonan PMB/000009	success	2026-08-04 06:53:55
589	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 26 ditolak: .\r\n	success	2026-08-04 06:54:58
590	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar baru' (ID: 26) pada permohonan PMB/000009	success	2026-08-04 09:08:40
591	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000001	success	2026-08-04 10:00:54
592	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 1 (1/2)	success	2026-08-04 10:21:22
593	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 1 ditolak: input keterangan	success	2026-08-04 10:25:27
594	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar' (ID: 1) pada permohonan PMB/000001	success	2026-08-04 10:39:08
595	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 1 disetujui di tahap operasi	success	2026-08-04 10:47:48
596	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 1 diupload	success	2026-08-04 10:48:42
597	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Truck Hydrant Despenser" ditambahkan ke permohonan ID 1 (2/2)	success	2026-08-04 10:50:33
598	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 2 ditolak: sim blur	success	2026-08-04 10:51:05
599	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 2) pada permohonan PMB/000001	success	2026-08-04 10:51:21
600	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 1 disetujui di tahap security	success	2026-08-04 10:51:31
601	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-08-04 10:52:06
602	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 10:52:11
603	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000001	success	2026-08-04 11:01:05
604	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 2 disetujui di tahap operasi	success	2026-08-04 11:32:41
605	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-08-04 11:33:49
606	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000001 untuk 1 unit GSE	success	2026-08-04 11:34:22
607	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar' (ID: 3) pada permohonan PMK/000001	success	2026-08-04 11:35:04
608	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 3 disetujui di tahap operasi	success	2026-08-04 11:35:17
609	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 3 diupload	success	2026-08-04 11:35:31
610	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 3 disetujui di tahap security	success	2026-08-04 11:48:53
611	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 1 (1/1)	success	2026-08-04 11:57:40
612	1	admin	admin	127.0.0.1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:32:56
613	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:35:10
614	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:35:28
615	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:36:31
616	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000002	success	2026-08-04 13:38:54
617	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:40:33
618	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:40:45
619	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:43:02
620	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:43:24
621	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:43:35
622	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:45:35
623	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:46:40
624	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:54:40
625	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:56:01
626	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:56:15
627	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 13:57:26
628	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 14:00:20
629	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 14:01:20
630	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 14:23:34
631	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Pallet Dolly" ditambahkan ke permohonan ID 3 (1/2)	success	2026-08-04 14:27:51
632	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 2 diupload	success	2026-08-04 14:28:37
633	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 2 disetujui di tahap security	success	2026-08-04 14:28:52
634	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 2 permohonan ID 1	success	2026-08-04 14:29:09
635	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 3 (2/2)	success	2026-08-04 14:31:51
636	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: lengkapi\r\n	success	2026-08-04 14:32:47
637	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar' (ID: 5) pada permohonan PMB/000002	success	2026-08-04 14:33:32
638	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 14:42:57
639	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 14:44:09
640	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 4 disetujui di tahap operasi	success	2026-08-04 14:48:40
641	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 4 diupload	success	2026-08-04 14:48:49
642	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 4 disetujui di tahap security	success	2026-08-04 14:49:38
643	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui di tahap operasi	success	2026-08-04 14:51:16
644	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui di tahap sales	success	2026-08-04 14:51:38
645	1	admin	admin	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui di tahap security	success	2026-08-04 14:51:44
646	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 5 disetujui di tahap operasi	success	2026-08-04 14:51:46
647	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-04 14:52:11
648	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-04 15:01:07
649	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:00:47
650	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:03:36
651	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:03:44
652	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-04 16:03:50
653	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:04:40
654	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:06:24
655	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 4 permohonan ID 3	success	2026-08-04 16:07:21
656	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 3	success	2026-08-04 16:08:10
657	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 4 permohonan ID 3	success	2026-08-04 16:08:15
658	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:08:31
659	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:09:03
660	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:10:52
661	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:13:05
662	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:13:50
663	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:14:49
664	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-04 16:34:51
665	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 3	success	2026-08-04 16:35:53
666	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 3 permohonan ID 2	success	2026-08-04 16:36:10
667	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-05 09:03:13
668	1	admin	admin	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 1	success	2026-08-05 09:03:23
669	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000003	success	2026-08-05 15:16:32
670	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 4 (1/1)	success	2026-08-05 15:18:13
671	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000004	success	2026-08-05 16:17:04
672	5	gh_garuda	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Belt Conveyor Loader" ditambahkan ke permohonan ID 5 (1/1)	success	2026-08-05 16:18:36
673	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 6 disetujui di tahap operasi	success	2026-08-05 16:18:50
674	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 6 diupload	success	2026-08-05 16:19:04
675	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 6 disetujui di tahap security	success	2026-08-05 16:19:45
676	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 7 disetujui di tahap operasi	success	2026-08-05 16:19:48
677	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 7 diupload	success	2026-08-05 16:20:00
678	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 7 disetujui di tahap security	success	2026-08-05 16:20:22
679	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 5	success	2026-08-05 16:21:26
680	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 6 permohonan ID 4	success	2026-08-05 16:24:00
681	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 5 diupload	success	2026-08-06 11:02:21
682	1	admin	admin	::1	Approval	tolak_item	Unit GSE ID 5 ditolak: .	success	2026-08-06 11:09:20
683	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Towing Bar' (ID: 5) pada permohonan PMB/000002	success	2026-08-06 11:09:36
684	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 5 disetujui di tahap operasi	success	2026-08-06 11:09:42
685	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 3	success	2026-08-06 15:47:44
686	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 3	success	2026-08-06 15:48:28
687	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 2 permohonan ID 1	success	2026-08-07 14:02:12
688	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000002 untuk 1 unit GSE	success	2026-08-07 14:10:37
689	14	gh_pelitaair	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Truck Hydrant Despenser' (ID: 8) pada permohonan PMK/000002	success	2026-08-07 14:12:05
690	1	admin	admin	::1	Approval	setujui_item	Unit GSE ID 8 disetujui di tahap operasi	success	2026-08-07 14:12:21
691	1	admin	admin	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 8 diupload	success	2026-08-07 14:16:01
692	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000002	success	2026-08-07 14:19:29
693	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000003	success	2026-08-07 14:21:50
694	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 5	success	2026-08-07 14:24:03
695	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 5	success	2026-08-07 14:24:07
696	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 7 permohonan ID 5	success	2026-08-07 14:25:25
697	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 5	success	2026-08-07 14:25:53
698	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 7 permohonan ID 5	success	2026-08-07 14:25:58
699	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 7 permohonan ID 5	success	2026-08-07 14:28:51
700	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 5	success	2026-08-07 14:29:10
701	5	gh_garuda	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 5	success	2026-08-07 14:31:47
702	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000005	success	2026-08-10 10:46:59
703	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Belt conveyor loader" ditambahkan ke permohonan ID 7 (1/1)	success	2026-08-10 10:48:30
704	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 4 permohonan ID 3	success	2026-08-10 11:02:39
705	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 9 disetujui di tahap operasi	success	2026-08-10 11:35:04
706	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 9 diupload	success	2026-08-10 14:11:54
707	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 9 disetujui di tahap security	success	2026-08-10 14:13:26
708	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 9 permohonan ID 7	success	2026-08-10 14:13:50
709	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 9 permohonan ID 7	success	2026-08-10 14:14:03
710	14	gh_pelitaair	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Belt conveyor loader" ditambahkan ke permohonan ID 2 (1/1)	success	2026-08-10 14:15:53
711	14	gh_pelitaair	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000003 untuk 1 unit GSE	success	2026-08-10 14:19:24
712	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 8 dihapus	success	2026-08-10 14:19:34
713	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Belt Conveyor Loader" ditambahkan ke permohonan ID 3 (1/2)	success	2026-08-10 14:23:41
714	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000004	success	2026-08-10 14:36:00
715	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Belt Conveyor Loader" ditambahkan ke permohonan ID 4 (1/1)	success	2026-08-10 14:36:25
716	5	gh_garuda	ground_handling	::1	Permohonan Keluar	hapus_permohonan	Permohonan ID 4 dihapus	success	2026-08-10 14:51:44
717	5	gh_garuda	ground_handling	::1	Permohonan Keluar	hapus_permohonan	Permohonan ID 3 dihapus	success	2026-08-10 14:51:49
718	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000003	success	2026-08-10 14:52:06
719	5	gh_garuda	ground_handling	::1	Permohonan Keluar	hapus_permohonan	Permohonan ID 5 dihapus	success	2026-08-10 14:57:01
720	5	gh_garuda	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000003	success	2026-08-10 14:57:17
721	5	gh_garuda	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Belt Conveyor Loader" ditambahkan ke permohonan ID 6 (1/1)	success	2026-08-10 14:57:36
722	2	unit_operasi	unit_operasi	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 6 disetujui	success	2026-08-10 14:58:33
723	4	unit_sales	unit_sales	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 6 disetujui	success	2026-08-10 15:18:24
724	11	unit_security	unit_security	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 6 disetujui	success	2026-08-10 15:19:13
725	11	unit_security	unit_security	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 6 permohonan ID 6	success	2026-08-10 15:19:17
726	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000004	success	2026-08-10 15:25:11
727	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 7 (1/1)	success	2026-08-10 15:25:38
728	2	unit_operasi	unit_operasi	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 7 disetujui	success	2026-08-10 15:27:27
729	4	unit_sales	unit_sales	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 7 disetujui	success	2026-08-10 15:28:22
730	11	unit_security	unit_security	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 7 disetujui	success	2026-08-10 15:29:51
731	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000006	success	2026-08-10 15:30:52
732	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 9 (1/1)	success	2026-08-10 15:44:14
733	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000007	success	2026-08-10 15:48:08
734	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 10 (1/1)	success	2026-08-10 15:50:07
735	5	gh_garuda	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000008	success	2026-08-11 09:59:17
736	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000009	success	2026-08-11 10:00:48
737	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 12 (1/1)	success	2026-08-11 10:01:50
738	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000009	success	2026-08-11 11:13:17
739	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 13 (1/1)	success	2026-08-11 11:14:08
740	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 14 disetujui di tahap operasi	success	2026-08-11 11:18:02
741	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000009	success	2026-08-11 11:27:13
742	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 14 (1/1)	success	2026-08-11 11:27:37
743	6	gh_lion	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 14 dihapus	success	2026-08-11 11:43:39
744	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000009	success	2026-08-11 11:43:59
745	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 15 (1/1)	success	2026-08-11 11:47:36
746	6	gh_lion	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 15 dihapus	success	2026-08-11 11:57:14
747	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000009	success	2026-08-11 11:57:34
748	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 16 (1/1)	success	2026-08-11 11:57:59
749	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 17 disetujui di tahap operasi	success	2026-08-11 14:32:02
750	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 17 diupload	success	2026-08-11 14:33:29
751	4	unit_sales	unit_sales	127.0.0.1	Approval	tolak_item	Unit GSE ID 17 ditolak: .\r\n	success	2026-08-11 15:03:31
752	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Water Service Truk' (ID: 17) pada permohonan PMB/000009	success	2026-08-11 15:04:12
753	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 17 disetujui di tahap operasi	success	2026-08-11 15:04:23
754	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 17 diupload	success	2026-08-11 15:04:37
755	11	unit_security	unit_security	127.0.0.1	Approval	setujui_item	Unit GSE ID 17 disetujui di tahap security	success	2026-08-11 15:07:50
756	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 17 permohonan ID 16	success	2026-08-11 15:08:14
757	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 17 permohonan ID 16	success	2026-08-11 15:08:47
758	6	gh_lion	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000003 untuk 1 unit GSE	success	2026-08-11 15:58:39
759	6	gh_lion	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 17 dihapus	success	2026-08-11 16:19:50
760	6	gh_lion	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000003 untuk 1 unit GSE	success	2026-08-11 16:19:56
761	6	gh_lion	ground_handling	::1	Permohonan Masuk	hapus_permohonan	Permohonan ID 18 dihapus	success	2026-08-11 16:26:02
762	6	gh_lion	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000003 untuk 1 unit GSE	success	2026-08-11 16:26:09
763	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Water Service Truk' (ID: 20) pada permohonan PMK/000003	success	2026-08-11 16:26:31
764	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 20 disetujui di tahap operasi	success	2026-08-11 16:26:45
765	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 20 diupload	success	2026-08-11 16:27:02
766	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 17 permohonan ID 16	success	2026-08-12 09:43:49
767	4	unit_sales	unit_sales	127.0.0.1	Approval	tolak_item	Unit GSE ID 20 ditolak: .	success	2026-08-12 09:44:45
768	6	gh_lion	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000003 untuk 1 unit GSE	success	2026-08-12 10:04:45
769	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Water Service Truk' (ID: 21) pada permohonan PMK/000003	success	2026-08-12 10:05:20
770	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 21 disetujui di tahap operasi	success	2026-08-12 10:05:44
771	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 21 diupload	success	2026-08-12 10:06:05
772	4	unit_sales	unit_sales	127.0.0.1	Approval	tolak_item	Unit GSE ID 21 ditolak: .	success	2026-08-12 10:17:17
773	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Water Service Truk' (ID: 21) pada permohonan PMK/000003	success	2026-08-12 10:26:16
774	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 21 disetujui di tahap operasi	success	2026-08-12 10:26:30
775	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 21 diupload	success	2026-08-12 10:26:44
776	2	unit_operasi	unit_operasi	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 17 permohonan ID 16	success	2026-08-12 11:26:01
777	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 1 permohonan ID 1	success	2026-08-12 11:52:15
778	1	admin	admin	::1	Permohonan Masuk	edit_jumlah_unit	Jumlah unit permohonan ID 16 diubah menjadi 2	success	2026-08-13 10:02:21
779	1	admin	admin	::1	Permohonan Masuk	edit_jumlah_unit	Jumlah unit permohonan ID 3 diubah menjadi 3	success	2026-08-13 10:15:36
780	1	admin	admin	::1	Permohonan Masuk	edit_jumlah_unit	Jumlah unit permohonan ID 11 diubah menjadi 1	success	2026-08-13 10:27:39
781	14	gh_pelitaair	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 7 (1/1)	success	2026-08-13 10:32:37
782	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 22 disetujui di tahap operasi	success	2026-08-13 10:33:01
783	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 22 diupload	success	2026-08-13 10:34:03
784	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 17 permohonan ID 16	success	2026-08-13 13:53:49
785	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 17 permohonan ID 16	success	2026-08-13 13:57:26
786	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 17 permohonan ID 16	success	2026-08-13 13:57:46
787	1	admin	admin	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 5	success	2026-08-13 13:58:16
788	1	admin	admin	::1	Users	ubah_status_user	User "GH Lion Air" (ID 6) diubah ke Tidak Aktif	success	2026-08-19 09:10:07
789	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 16	success	2026-08-19 09:37:17
790	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 16	success	2026-08-19 09:38:24
791	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-19 09:38:39
792	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-19 09:39:38
793	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-19 09:40:16
794	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-19 09:46:01
795	1	admin	admin	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-19 09:46:22
796	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-19 09:46:39
797	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 16	success	2026-08-19 09:47:22
798	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 16	success	2026-08-19 09:48:25
799	1	admin	admin	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 1	success	2026-08-19 09:48:37
800	1	admin	admin	::1	Users	ubah_status_user	User "GH Lion Air" (ID 6) diubah ke Aktif	success	2026-08-19 10:37:35
801	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000001	success	2026-08-19 11:02:54
802	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 1 (1/2)	success	2026-08-19 11:03:47
803	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 1 (2/2)	success	2026-08-19 11:06:40
804	2	unit_operasi	unit_operasi	::1	Approval	tolak_item	Unit GSE ID 1 ditolak: lengkapi gambar unit	success	2026-08-19 11:08:35
805	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 1 (1/2)	success	2026-08-19 11:17:07
806	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 1 (1/2)	success	2026-08-19 11:20:39
807	6	gh_lion	ground_handling	::1	Permohonan Masuk	tambah_masuk_baru	Nomor: PMB/000001	success	2026-08-19 11:49:25
808	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 2 (1/1)	success	2026-08-19 12:17:55
809	1	admin	admin	::1	Permohonan Masuk	edit_jumlah_unit	Jumlah unit permohonan ID 2 diubah menjadi 2	success	2026-08-19 12:18:27
810	6	gh_lion	ground_handling	::1	Permohonan Masuk	input_kelengkapan	Unit "Water Service Truk" ditambahkan ke permohonan ID 2 (2/2)	success	2026-08-19 12:20:43
811	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 5 disetujui di tahap operasi	success	2026-08-19 12:21:52
812	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 6 disetujui di tahap operasi	success	2026-08-19 12:21:54
813	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 6 diupload	success	2026-08-19 12:23:13
814	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 5 diupload	success	2026-08-19 12:23:35
815	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 5 disetujui di tahap security	success	2026-08-19 12:29:52
816	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 6 disetujui di tahap security	success	2026-08-19 12:29:54
817	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 6 permohonan ID 2	success	2026-08-19 12:38:26
818	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 5 permohonan ID 2	success	2026-08-19 12:38:34
819	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 6 permohonan ID 2	success	2026-08-19 12:38:39
820	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 6 permohonan ID 2	success	2026-08-19 12:38:47
821	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 5 permohonan ID 2	success	2026-08-19 12:39:46
822	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-08-19 12:40:41
823	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-08-19 12:40:50
824	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 5 permohonan ID 2	success	2026-08-19 12:40:56
825	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-08-19 12:41:00
826	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-08-19 12:41:34
827	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker	Cetak stiker untuk permohonan ID 2	success	2026-08-19 12:41:37
828	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-08-19 12:41:46
829	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-08-19 12:41:57
830	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 5 permohonan ID 2	success	2026-08-19 12:42:08
831	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-08-19 12:42:11
832	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba	Cetak BA untuk permohonan ID 2	success	2026-08-19 12:46:28
833	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 5 permohonan ID 2	success	2026-08-19 12:46:56
834	6	gh_lion	ground_handling	::1	Permohonan Keluar	tambah_keluar_baru	Nomor: PKB/000001	success	2026-08-19 12:47:53
835	6	gh_lion	ground_handling	::1	Permohonan Keluar	input_kelengkapan	Unit "Towing Bar" ditambahkan ke permohonan ID 1 (1/2)	success	2026-08-19 12:54:50
836	1	admin	admin	::1	Permohonan Masuk	edit_jumlah_unit	Jumlah unit permohonan ID 1 diubah menjadi 1	success	2026-08-19 14:01:45
837	2	unit_operasi	unit_operasi	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui	success	2026-08-19 14:02:14
838	4	unit_sales	unit_sales	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui	success	2026-08-19 14:02:54
839	11	unit_security	unit_security	::1	Approval	setujui_item_keluar	Unit GSE keluar ID 1 disetujui	success	2026-08-19 14:20:51
840	11	unit_security	unit_security	::1	Permohonan Keluar	cetak_ba_item	Cetak BA item ID 1 permohonan ID 1	success	2026-08-19 14:21:08
841	6	gh_lion	ground_handling	::1	GSE	perbarui_kontrak	Membuat permohonan perbaruan kontrak PMK/000001 untuk 1 unit GSE	success	2026-08-19 14:22:14
842	6	gh_lion	ground_handling	::1	permohonan_masuk	ajukan_ulang_item	Ajukan ulang unit GSE 'Water Service Truk' (ID: 7) pada permohonan PMK/000001	success	2026-08-19 14:23:33
843	2	unit_operasi	unit_operasi	::1	Approval	setujui_item	Unit GSE ID 7 disetujui di tahap operasi	success	2026-08-19 14:24:42
844	3	unit_equipment	unit_equipment	::1	Approval	upload_ba_item	BA Uji Laik unit GSE ID 7 diupload	success	2026-08-19 14:24:59
845	11	unit_security	unit_security	::1	Approval	setujui_item	Unit GSE ID 7 disetujui di tahap security	success	2026-08-19 14:26:08
846	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 3	success	2026-08-19 14:26:46
847	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 7 permohonan ID 3	success	2026-08-19 14:27:03
848	11	unit_security	unit_security	::1	Permohonan Masuk	cetak_ba_item	Cetak BA item ID 7 permohonan ID 3	success	2026-08-19 14:27:19
849	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 3	success	2026-08-19 14:28:27
850	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 3	success	2026-08-19 14:28:48
851	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 3	success	2026-08-19 14:29:42
852	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 7 permohonan ID 3	success	2026-08-19 14:36:29
853	6	gh_lion	ground_handling	::1	Permohonan Masuk	cetak_stiker_item	Cetak stiker item ID 6 permohonan ID 2	success	2026-08-19 14:36:44
\.


--
-- TOC entry 5171 (class 0 OID 33408)
-- Dependencies: 221
-- Data for Name: airlines; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.airlines (id_airline, nama_airline, kode_airline, created_at, status) FROM stdin;
4	PT Angkasa Pura	AP	2026-06-25 09:31:23.102222	Aktif
1	Garuda Indonesia	GA	2026-06-24 15:15:58.029937	Aktif
2	Lion Air	JT	2026-06-24 15:15:58.029937	Aktif
6	Pelita Air	IP	2026-06-29 09:55:09	Aktif
3	Citilink	CT	2026-06-24 15:15:58.029937	Aktif
7	Non-Airline	NA	2026-07-28 11:26:46	Aktif
\.


--
-- TOC entry 5173 (class 0 OID 33419)
-- Dependencies: 223
-- Data for Name: blocked_ip; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.blocked_ip (id, ip_address, alasan, blocked_at, blocked_until, is_active) FROM stdin;
\.


--
-- TOC entry 5175 (class 0 OID 33431)
-- Dependencies: 225
-- Data for Name: detail_permohonan_keluar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detail_permohonan_keluar (id, id_permohonan_keluar, nama_gse, manufacture_type, no_asset, created_at, sticker_ap, file_bukti_kerusakan, file_foto_gse, alasan_penolakan, verifikasi_oleh, verifikasi_at, tahap_saat_ini, status_item, alasan_penolakan_item, jenis_item, keterangan) FROM stdin;
1	1	Towing Bar	Non Motorized	NMT-2026-0001	2026-08-19 12:54:50	GE.UPG.JT.000001.5	\N	PKB_000001/bukti_unit_gse_1787115290_6a85371a7b30a.jpg	\N	11	2026-08-19 14:20:51	selesai	Selesai	\N	Keluar Baru	Keluar Hapus Asset
\.


--
-- TOC entry 5177 (class 0 OID 33443)
-- Dependencies: 227
-- Data for Name: detail_permohonan_masuk; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detail_permohonan_masuk (id, id_permohonan_masuk, file_ktp, file_tim, file_stnk, file_sim, created_at, file_bukti_perbaikan, nama_gse, manufacture_type, no_asset, sticker_ap, file_foto_gse, status_item, alasan_penolakan_item, diproses_oleh, diproses_at, status_operasi, alasan_operasi, operasi_oleh, operasi_at, status_equipment, alasan_equipment, equipment_oleh, equipment_at, status_sales, alasan_sales, sales_oleh, sales_at, status_security, alasan_security, security_oleh, security_at, tahap_saat_ini, verifikasi_oleh, verifikasi_at, file_ba_uji_laik, status_kelayakan, dimensi_p, dimensi_l, dimensi_luas, masa_mulai, masa_selesai, file_emisi, dimensi_diisi_pertama_at, dimensi_edit_count, jenis_nomor_diubah, nomor_baru, file_perubahan_rangka, file_surat_rekomendasi, file_pass_kendaraan, nomor_rangka, nomor_mesin, file_perubahan_mesin, file_foto_rangka, file_foto_mesin, jenis_unit, jenis_item, keterangan) FROM stdin;
7	3	PMK_000001/file_ktp_1787120613_6a854be579303.pdf	PMK_000001/file_tim_1787120613_6a854be579489.pdf	PMK_000001/file_stnk_1787120613_6a854be5796f9.pdf	PMK_000001/file_sim_1787120613_6a854be57990d.pdf	2026-08-19 14:22:14	\N	Water Service Truk	Motorized	MTR-2026-0001	GE.UPG.JT.000001.7	PMK_000001/file_foto_gse_1787120613_6a854be5791a5.jpg	Selesai	\N	\N	\N	Menunggu	\N	2	\N	Menunggu	\N	3	\N	Menunggu	\N	4	\N	Menunggu	\N	11	\N	selesai	11	2026-08-19 14:26:08	PMK_000001/ba_uji_laik_item_1787120699_6a854c3b72f65.pdf	Layak	6.00	2.20	13.20	2027-01-01	2027-12-31	PMK_000001/file_emisi_1787120613_6a854be579aa7.pdf	2026-08-19 14:25:31	0	\N	\N	\N	\N	\N	MHYKZE81SCJ115045	2GR-FE1234567	\N	\N	\N	\N	\N	Perbaruan kontrak untuk 1 unit GSE.
6	2	PMB_000001/file_ktp_1787113243_6a852f1b9d4a1.pdf	PMB_000001/file_tim_1787113243_6a852f1b9e06f.pdf	PMB_000001/file_stnk_1787113243_6a852f1b9ef0d.pdf	PMB_000001/file_sim_1787113243_6a852f1b9fdc1.pdf	2026-08-19 12:20:43	\N	Water Service Truk	Motorized	MTR-2026-0001	GE.UPG.JT.000001.6	PMB_000001/bukti_unit_gse_1787113243_6a852f1b9c5a4.jpg	Selesai	\N	\N	\N	Menunggu	\N	2	\N	Menunggu	\N	3	\N	Menunggu	\N	4	\N	Menunggu	\N	11	\N	selesai	11	2026-08-19 12:29:54	PMB_000001/ba_uji_laik_item_1787113393_6a852fb194334.pdf	Layak	6.00	2.20	13.20	2026-08-19	2026-12-31	PMB_000001/file_emisi_1787113243_6a852f1ba090a.pdf	2026-08-19 12:28:20	0	\N	\N	\N	PMB_000001/surat_rekomendasi_1787114166_6a8532b66d41c.pdf	PMB_000001/pass_kendaraan_1787114293_6a853335b68db.pdf	MHYKZE81SCJ115045	2GR-FE1234567	\N	PMB_000001/file_foto_rangka_1787113243_6a852f1ba14e2.pdf	PMB_000001/file_foto_mesin_1787113243_6a852f1ba1f33.pdf	\N	Masuk Baru	Penambahan Alat
5	2	\N	\N	\N	\N	2026-08-19 12:17:55	\N	Towing Bar	Non Motorized	NMT-2026-0001	GE.UPG.JT.000001.5	PMB_000001/bukti_unit_gse_1787113075_6a852e731feb1.jpg	Selesai	\N	\N	\N	Menunggu	\N	2	\N	Menunggu	\N	3	\N	Menunggu	\N	4	\N	Menunggu	\N	11	\N	selesai	11	2026-08-19 12:29:52	PMB_000001/ba_uji_laik_item_1787113415_6a852fc70204d.pdf	Layak	3.50	0.50	1.75	2026-08-19	2026-12-31	\N	2026-08-19 12:28:43	0	\N	\N	\N	PMB_000001/surat_rekomendasi_1787114158_6a8532ae7be24.pdf	\N	\N	\N	\N	\N	\N	\N	Masuk Baru	Penambahan Alat
\.


--
-- TOC entry 5178 (class 0 OID 33460)
-- Dependencies: 228
-- Data for Name: gse; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gse (id_gse, nama_gse, status, created_at, updated_at, id_airline, manufacture_type, no_asset, sticker_ap, nomor_rangka, nomor_mesin, masa_mulai, masa_selesai) FROM stdin;
1	Towing Bar	Tidak Aktif	2026-08-19 12:29:52	2026-08-19 12:46:56	2	Non Motorized	NMT-2026-0001	GE.UPG.JT.000001.5	\N	\N	2026-08-19	2026-12-31
2	Water Service Truk	Tidak Aktif	2026-08-19 12:29:54	2026-08-19 14:36:44	2	Motorized	MTR-2026-0001	GE.UPG.JT.000001.6	MHYKZE81SCJ115045	2GR-FE1234567	2026-08-19	2026-12-31
3	Water Service Truk	Aktif	2026-08-19 14:26:08	2026-08-19 14:36:44	2	Motorized	MTR-2026-0001	GE.UPG.JT.000001.6	MHYKZE81SCJ115045	2GR-FE1234567	2027-01-01	2027-12-31
\.


--
-- TOC entry 5180 (class 0 OID 33477)
-- Dependencies: 230
-- Data for Name: ids_ancaman; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ids_ancaman (id, tipe, payload, ip_address, created_at) FROM stdin;
1	credential_stuffing	username: gh_garuda, 3x gagal dari IP ::1	::1	2026-08-10 06:22:30
2	credential_stuffing	username: gh_garuda, 4x gagal dari IP ::1	::1	2026-08-10 06:22:38
3	credential_stuffing	username: unit_security, 3x gagal dari IP ::1	::1	2026-08-10 07:28:59
4	credential_stuffing	username: unit_security, 4x gagal dari IP ::1	::1	2026-08-10 07:29:18
5	credential_stuffing	username: admin, 3x gagal dari IP ::1	::1	2026-08-13 05:54:29
\.


--
-- TOC entry 5182 (class 0 OID 33488)
-- Dependencies: 232
-- Data for Name: login_log; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.login_log (id, username, ip_address, user_agent, status, keterangan, created_at) FROM stdin;
163	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-28 23:43:14
164	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-28 23:45:29
165	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-28 23:51:41
166	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-28 23:51:53
167	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-28 23:52:23
168	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-28 23:52:34
169	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-28 23:59:26
170	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-28 23:59:47
171	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-29 00:00:19
172	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 00:00:29
173	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-29 00:00:47
174	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 00:00:59
175	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-29 00:14:49
176	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 00:15:01
177	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-29 00:15:09
178	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 00:15:18
179	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-29 00:26:57
180	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 00:27:04
181	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 03:09:52
182	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 03:10:26
183	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 07:39:19
184	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 07:39:39
185	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-29 08:06:16
186	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 08:06:30
187	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-29 08:12:36
188	gh_lion2	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 08:13:19
189	gh_lion2	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-29 09:14:30
190	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-29 09:14:47
191	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-30 02:56:06
192	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-30 08:16:47
193	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-30 08:17:51
194	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-31 02:56:28
195	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-31 02:57:13
196	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-31 04:10:12
197	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-31 04:10:23
198	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-31 04:14:06
199	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-31 04:14:14
200	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-31 04:15:50
201	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-07-31 04:16:00
202	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-07-31 04:16:15
203	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-07-31 04:30:01
204	gh_citilink	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-03 08:00:36
205	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-03 08:02:23
206	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-04 00:29:59
207	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-04 00:53:15
208	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-04 03:03:28
209	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-04 03:03:45
210	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-05 03:02:39
211	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-05 08:34:58
212	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-05 08:58:19
213	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-05 08:58:43
214	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-08-05 09:06:42
215	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-05 09:06:53
216	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Logout	2026-08-05 09:18:25
217	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	success	Login berhasil	2026-08-05 09:18:32
218	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-08-06 03:04:34
219	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-08-06 03:04:46
220	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-06 03:04:57
221	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-06 03:05:30
222	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-06 04:26:18
223	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-06 04:59:32
224	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-08-06 09:47:28
225	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-06 09:47:38
226	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-07 02:58:59
227	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-07 07:59:16
228	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-07 08:00:12
229	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-07 08:21:08
230	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-07 08:21:32
231	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-07 10:04:32
232	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-07 10:04:41
233	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 02:14:47
234	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 02:15:00
235	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 02:15:19
236	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 02:15:22
237	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 02:15:48
238	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 02:46:40
239	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 02:48:48
240	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 02:48:59
241	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 03:01:50
242	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 03:01:59
243	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 03:02:05
244	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 03:02:12
245	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 03:29:55
246	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 03:30:04
247	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 03:35:07
248	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 03:35:17
249	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 03:35:52
250	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 03:36:00
251	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 03:51:54
252	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 03:52:05
253	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 05:30:43
254	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 05:31:01
255	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 06:11:58
256	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 06:12:31
257	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 06:12:55
258	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-08-10 06:13:10
259	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 06:13:20
260	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 06:14:26
261	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 06:14:45
262	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 06:21:53
263	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-08-10 06:22:22
264	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-08-10 06:22:30
265	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-08-10 06:22:38
266	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 06:22:51
267	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 06:23:52
268	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 06:24:16
269	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 06:30:39
270	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 06:35:07
271	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 06:57:56
272	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Password salah dari IP ::1	2026-08-10 06:58:10
273	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 06:58:23
274	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 07:11:52
275	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 07:12:06
276	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 07:18:02
277	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 07:18:14
278	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 07:18:57
279	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 07:19:09
280	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 07:19:31
281	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 07:20:15
282	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 07:24:45
283	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 07:24:53
284	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	failed	Password salah dari IP ::1	2026-08-10 07:27:00
285	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	failed	Password salah dari IP ::1	2026-08-10 07:27:00
286	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-10 07:27:14
287	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Logout	2026-08-10 07:27:48
288	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-10 07:28:13
289	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	failed	Password salah dari IP ::1	2026-08-10 07:28:59
290	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	failed	Password salah dari IP ::1	2026-08-10 07:29:18
291	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-10 07:29:31
292	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 07:44:51
293	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 07:45:00
294	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-10 07:46:33
295	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-10 07:47:08
296	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-11 01:58:05
297	gh_garuda	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-11 01:59:59
298	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-11 02:00:08
299	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-11 02:00:30
300	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-11 03:14:38
301	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-11 03:14:50
302	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-11 03:17:50
303	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-11 03:18:19
304	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-11 05:46:40
305	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-11 06:31:57
306	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-11 06:32:13
307	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Logout	2026-08-11 06:32:59
308	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-11 06:33:13
309	unit_sales	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0	success	Login berhasil	2026-08-11 06:33:43
310	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-11 07:03:44
311	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-11 07:03:51
312	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-11 07:03:58
313	unit_security	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0	success	Login berhasil	2026-08-11 07:07:46
314	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Logout	2026-08-11 07:35:16
315	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-11 07:36:07
316	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Logout	2026-08-11 07:58:00
317	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-11 07:58:03
318	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-12 01:21:07
319	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-12 01:22:23
320	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-12 01:39:43
321	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-12 01:40:05
322	unit_sales	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	success	Login berhasil	2026-08-12 01:41:21
323	unit_security	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	success	Login berhasil	2026-08-12 01:41:42
324	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-12 03:24:31
325	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-12 03:42:25
326	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-12 03:42:34
327	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-13 01:55:14
328	gh_pelitaair	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-13 02:16:03
329	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-13 02:32:57
330	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-13 02:33:22
331	unit_sales	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	success	Login berhasil	2026-08-13 02:34:26
332	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-13 03:20:31
333	admin	::1	Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1	failed	Password salah dari IP ::1	2026-08-13 03:32:28
334	admin	::1	Mozilla/5.0 (iPhone; CPU iPhone OS 26_5_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/151.0.7922.112 Mobile/15E148 Safari/604.1	success	Login berhasil	2026-08-13 03:32:42
335	admin	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	failed	Password salah dari IP ::1	2026-08-13 05:54:06
336	admin	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	failed	Password salah dari IP ::1	2026-08-13 05:54:18
337	admin	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	failed	Password salah dari IP ::1	2026-08-13 05:54:28
338	admin	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	success	Login berhasil	2026-08-13 05:54:42
339	admin	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	success	Logout	2026-08-13 05:55:59
340	gh_lion	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	success	Login berhasil	2026-08-13 05:56:10
341	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-13 06:31:41
342	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-13 07:39:43
343	gh_lion	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	success	Logout	2026-08-13 08:24:46
344	admin	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	failed	Password salah dari IP ::1	2026-08-13 08:25:41
345	admin	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	success	Login berhasil	2026-08-13 08:25:56
346	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-13 08:27:05
347	admin	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36	success	Logout	2026-08-13 08:27:39
348	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-17 01:48:43
349	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-17 01:48:49
350	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-17 01:49:33
351	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-19 00:58:59
352	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Logout	2026-08-19 01:04:09
353	admin	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-19 01:04:30
354	gh_lion	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	success	Login berhasil	2026-08-19 01:11:11
355	gh_lion	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	success	Logout	2026-08-19 01:14:23
356	gh_lion	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	failed	Percobaan login akun nonaktif dari IP ::1	2026-08-19 01:14:38
357	gh_citilink	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	failed	Password salah dari IP ::1	2026-08-19 01:17:45
358	gh_citilink	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	success	Login berhasil	2026-08-19 01:17:58
359	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	failed	Percobaan login akun nonaktif dari IP ::1	2026-08-19 02:37:24
360	gh_lion	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36	success	Login berhasil	2026-08-19 02:37:55
361	gh_citilink	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	success	Logout	2026-08-19 02:38:33
362	unit_sales	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	success	Login berhasil	2026-08-19 02:38:48
363	unit_sales	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	success	Logout	2026-08-19 03:07:21
364	unit_operasi	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	failed	Password salah dari IP ::1	2026-08-19 03:07:57
365	unit_operasi	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	success	Login berhasil	2026-08-19 03:08:12
366	unit_operasi	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-19 04:21:34
367	unit_equipment	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0	success	Login berhasil	2026-08-19 04:22:44
368	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	failed	Password salah dari IP ::1	2026-08-19 04:24:44
369	unit_sales	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	success	Login berhasil	2026-08-19 04:25:00
370	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	success	Login berhasil	2026-08-19 04:29:29
371	unit_security	::1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	success	Login berhasil	2026-08-19 06:18:00
372	gh_lion	::1	Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Mobile Safari/537.36	success	Login berhasil	2026-08-19 06:28:10
\.


--
-- TOC entry 5184 (class 0 OID 33500)
-- Dependencies: 234
-- Data for Name: permohonan_keluar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permohonan_keluar (id_permohonan_keluar, nomor_permohonan, tanggal_keluar, status, created_by, created_at, updated_at, id_airline, nama_pemohon, asal_instansi, alasan_penolakan, verifikasi_operasi, verifikasi_operasi_at, verifikasi_operasi_oleh, verifikasi_equipment, verifikasi_equipment_at, verifikasi_equipment_oleh, verifikasi_sales, verifikasi_sales_at, verifikasi_sales_oleh, tujuan_keluar, status_perbaikan, jenis_permohonan, verifikasi_security, verifikasi_security_at, verifikasi_security_oleh, nomor_surat, jumlah_unit_gse, file_bukti_permohonan, keterangan, jumlah_unit_edit_count, jumlah_unit_diedit_pertama_at) FROM stdin;
1	PKB/000001	2026-08-19	Disetujui	6	2026-08-19 12:47:53	2026-08-19 14:20:51	2	\N	Lion Air	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Perbaikan	\N	Keluar Baru	\N	\N	\N	101/GH-LA/VII/2026	1	PKB_000001/file_bukti_permohonan_1787114873_6a8535795cf6d.pdf	\N	1	2026-08-19 14:01:45
\.


--
-- TOC entry 5186 (class 0 OID 33520)
-- Dependencies: 236
-- Data for Name: permohonan_masuk; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permohonan_masuk (id_permohonan_masuk, nomor_permohonan, tanggal_masuk, asal_instansi, keterangan, status, alasan_penolakan, verifikasi_operasi, verifikasi_operasi_at, verifikasi_operasi_oleh, verifikasi_equipment, verifikasi_equipment_at, verifikasi_equipment_oleh, verifikasi_sales, verifikasi_sales_at, verifikasi_sales_oleh, created_by, created_at, updated_at, id_airline, id_permohonan_keluar, jenis_permohonan, jam_masuk, driver, file_surat_permohonan, verifikasi_security, verifikasi_security_at, verifikasi_security_oleh, jumlah_unit_gse, nomor_surat, file_bukti_permohonan, jumlah_unit_edit_count, jumlah_unit_diedit_pertama_at) FROM stdin;
2	PMB/000001	2026-08-19	Lion Air	\N	Disetujui	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	2026-08-19 11:49:25	2026-08-19 12:29:54	2	\N	Masuk Baru	\N	\N	\N	\N	\N	\N	2	001/GH-LA/VII/2026	PMB_000001/file_bukti_permohonan_1787111365_6a8527c5a7729.pdf	1	2026-08-19 12:18:27
3	PMK/000001	2026-08-19	Lion Air	Perbaruan kontrak untuk 1 unit GSE.	Disetujui	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	2026-08-19 14:22:14	2026-08-19 14:26:08	2	\N	Perbaruan Kontrak	\N	\N	\N	\N	\N	\N	1	\N	\N	0	\N
\.


--
-- TOC entry 5189 (class 0 OID 33540)
-- Dependencies: 239
-- Data for Name: riwayat_verifikasi_gse; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.riwayat_verifikasi_gse (id, id_detail, tahap, aksi, oleh, catatan, created_at) FROM stdin;
1	1	operasi	Ditolak	1	input keterangan	2026-08-04 10:25:27
2	1	operasi	Disetujui	1	\N	2026-08-04 10:47:48
3	1	equipment	Disetujui	1	\N	2026-08-04 10:48:42
4	1	sales	Disetujui	1	\N	2026-08-04 10:49:02
5	1	sales	Input Dimensi	1	{"dimensi_p":"5.2","dimensi_l":"1.1","dimensi_luas":5.72,"masa_mulai":"2026-08-04","masa_selesai":"2026-12-31"}	2026-08-04 10:49:02
6	2	operasi	Ditolak	1	sim blur	2026-08-04 10:51:05
7	1	security	Disetujui	1	\N	2026-08-04 10:51:31
8	2	operasi	Disetujui	1	\N	2026-08-04 11:32:41
9	1	sales	Edit Dimensi	1	{"sebelum":{"dimensi_p":"5.20","dimensi_l":"1.10","dimensi_luas":"5.72","masa_mulai":"2026-08-04","masa_selesai":"2026-12-31"},"sesudah":{"dimensi_p":"5.07","dimensi_l":"1.1","dimensi_luas":5.58,"masa_mulai":"2026-08-04","masa_selesai":"2026-12-31"}}	2026-08-04 11:33:11
10	3	operasi	Disetujui	1	\N	2026-08-04 11:35:17
11	3	equipment	Disetujui	1	\N	2026-08-04 11:35:31
12	3	sales	Disetujui	1	\N	2026-08-04 11:36:03
13	3	sales	Input Dimensi	1	{"dimensi_p":"5.07","dimensi_l":"1.1","dimensi_luas":5.58,"masa_mulai":"2027-01-01","masa_selesai":"2027-12-31"}	2026-08-04 11:36:03
14	3	security	Disetujui	1	\N	2026-08-04 11:48:53
15	2	equipment	Disetujui	1	\N	2026-08-04 14:28:37
16	2	sales	Disetujui	1	\N	2026-08-04 14:28:45
17	2	sales	Input Dimensi	1	{"dimensi_p":"2","dimensi_l":"2","dimensi_luas":4,"masa_mulai":"2026-08-04","masa_selesai":"2026-12-31"}	2026-08-04 14:28:45
18	2	security	Disetujui	1	\N	2026-08-04 14:28:52
19	5	operasi	Ditolak	1	lengkapi\r\n	2026-08-04 14:32:47
20	4	operasi	Disetujui	1	\N	2026-08-04 14:48:40
21	4	equipment	Disetujui	1	\N	2026-08-04 14:48:49
22	4	sales	Disetujui	1	\N	2026-08-04 14:49:15
23	4	sales	Input Dimensi	1	{"dimensi_p":"0.01","dimensi_l":"0.01","dimensi_luas":0,"masa_mulai":"2026-08-04","masa_selesai":"2026-12-31"}	2026-08-04 14:49:15
24	4	security	Disetujui	1	\N	2026-08-04 14:49:38
25	5	operasi	Disetujui	1	\N	2026-08-04 14:51:46
26	6	operasi	Disetujui	1	\N	2026-08-05 16:18:50
27	6	equipment	Disetujui	1	\N	2026-08-05 16:19:04
28	6	sales	Disetujui	1	\N	2026-08-05 16:19:16
29	6	sales	Input Dimensi	1	{"dimensi_p":"7.8","dimensi_l":"2.15","dimensi_luas":16.77,"masa_mulai":"2026-08-05","masa_selesai":"2026-12-31"}	2026-08-05 16:19:16
30	6	security	Disetujui	1	\N	2026-08-05 16:19:45
31	7	operasi	Disetujui	1	\N	2026-08-05 16:19:48
32	7	equipment	Disetujui	1	\N	2026-08-05 16:20:00
33	7	sales	Disetujui	1	\N	2026-08-05 16:20:14
34	7	sales	Input Dimensi	1	{"dimensi_p":"3.75","dimensi_l":"2.12","dimensi_luas":7.95,"masa_mulai":"2026-08-05","masa_selesai":"2026-12-31"}	2026-08-05 16:20:14
35	7	security	Disetujui	1	\N	2026-08-05 16:20:22
36	5	equipment	Disetujui	1	\N	2026-08-06 11:02:21
37	5	sales	Disetujui	1	\N	2026-08-06 11:02:35
38	5	sales	Input Dimensi	1	{"dimensi_p":"2.75","dimensi_l":"1.02","dimensi_luas":2.81,"masa_mulai":"2026-08-06","masa_selesai":"2026-12-31"}	2026-08-06 11:02:35
39	5	security	Ditolak	1	.	2026-08-06 11:09:20
40	5	operasi	Disetujui	1	\N	2026-08-06 11:09:42
41	8	operasi	Disetujui	1	\N	2026-08-07 14:12:21
42	8	equipment	Disetujui	1	\N	2026-08-07 14:16:01
43	6	sales	Edit Dimensi	1	{"sebelum":{"dimensi_p":"7.80","dimensi_l":"2.15","dimensi_luas":"16.77","masa_mulai":"2026-08-05","masa_selesai":"2026-12-31"},"sesudah":{"dimensi_p":"7.99","dimensi_l":"2.15","dimensi_luas":17.18,"masa_mulai":"2026-08-05","masa_selesai":"2026-12-31"}}	2026-08-07 14:17:34
44	9	operasi	Disetujui	2	\N	2026-08-10 11:35:04
45	9	equipment	Disetujui	3	\N	2026-08-10 14:11:54
46	9	sales	Disetujui	4	\N	2026-08-10 14:12:41
47	9	sales	Input Dimensi	4	{"dimensi_p":"8.4","dimensi_l":"2.75","dimensi_luas":23.1,"masa_mulai":"2026-08-10","masa_selesai":"2026-12-31"}	2026-08-10 14:12:41
48	9	security	Disetujui	11	\N	2026-08-10 14:13:26
49	14	operasi	Disetujui	2	\N	2026-08-11 11:18:01
50	17	operasi	Disetujui	2	\N	2026-08-11 14:32:02
51	17	equipment	Disetujui	3	\N	2026-08-11 14:33:29
52	17	sales	Ditolak	4	.\r\n	2026-08-11 15:03:31
53	17	operasi	Disetujui	2	\N	2026-08-11 15:04:23
54	17	equipment	Disetujui	3	\N	2026-08-11 15:04:37
55	6	sales	Edit Dimensi	4	{"sebelum":{"dimensi_p":"7.99","dimensi_l":"2.15","dimensi_luas":"17.18","masa_mulai":"2026-08-05","masa_selesai":"2026-12-31"},"sesudah":{"dimensi_p":"7.85","dimensi_l":"2.15","dimensi_luas":16.88,"masa_mulai":"2026-08-05","masa_selesai":"2026-12-31"}}	2026-08-11 15:05:39
56	17	sales	Disetujui	4	\N	2026-08-11 15:07:18
57	17	sales	Input Dimensi	4	{"dimensi_p":"7.85","dimensi_l":"2.15","dimensi_luas":16.88,"masa_mulai":"2026-08-11","masa_selesai":"2026-12-31"}	2026-08-11 15:07:18
58	17	security	Disetujui	11	\N	2026-08-11 15:07:50
59	20	operasi	Disetujui	2	\N	2026-08-11 16:26:45
60	20	equipment	Disetujui	3	\N	2026-08-11 16:27:02
61	20	sales	Ditolak	4	.	2026-08-12 09:44:45
62	21	operasi	Disetujui	2	\N	2026-08-12 10:05:44
63	21	equipment	Disetujui	3	\N	2026-08-12 10:06:05
64	21	sales	Ditolak	4	.	2026-08-12 10:17:17
65	21	operasi	Disetujui	2	\N	2026-08-12 10:26:30
66	21	equipment	Disetujui	3	\N	2026-08-12 10:26:44
67	22	operasi	Disetujui	2	\N	2026-08-13 10:33:01
68	22	equipment	Disetujui	3	\N	2026-08-13 10:34:03
69	1	operasi	Ditolak	2	lengkapi gambar unit	2026-08-19 11:08:35
70	5	operasi	Disetujui	2	\N	2026-08-19 12:21:52
71	6	operasi	Disetujui	2	\N	2026-08-19 12:21:54
72	6	equipment	Disetujui	3	\N	2026-08-19 12:23:13
73	5	equipment	Disetujui	3	\N	2026-08-19 12:23:35
74	6	sales	Disetujui	4	\N	2026-08-19 12:28:20
75	6	sales	Input Dimensi	4	{"dimensi_p":"6","dimensi_l":"2.2","dimensi_luas":13.2,"masa_mulai":"2026-08-19","masa_selesai":"2026-12-31"}	2026-08-19 12:28:20
76	5	sales	Disetujui	4	\N	2026-08-19 12:28:43
77	5	sales	Input Dimensi	4	{"dimensi_p":"3.5","dimensi_l":"0.5","dimensi_luas":1.75,"masa_mulai":"2026-08-19","masa_selesai":"2026-12-31"}	2026-08-19 12:28:43
78	5	security	Disetujui	11	\N	2026-08-19 12:29:52
79	6	security	Disetujui	11	\N	2026-08-19 12:29:54
80	7	operasi	Disetujui	2	\N	2026-08-19 14:24:42
81	7	equipment	Disetujui	3	\N	2026-08-19 14:24:59
82	7	sales	Disetujui	4	\N	2026-08-19 14:25:31
83	7	sales	Input Dimensi	4	{"dimensi_p":"6.00","dimensi_l":"2.2","dimensi_luas":13.2,"masa_mulai":"2027-01-01","masa_selesai":"2027-12-31"}	2026-08-19 14:25:31
84	7	security	Disetujui	11	\N	2026-08-19 14:26:08
\.


--
-- TOC entry 5191 (class 0 OID 33552)
-- Dependencies: 241
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id_user, username, password, nama, role, created_at, id_airline, status) FROM stdin;
2	unit_operasi	2581feaf1135c2ffe0efb3077c0fed21	Inggrid Adellia	unit_operasi	2026-06-24 11:43:41.839916	\N	Aktif
11	unit_security	e62f96d5274e01f3fb021009ceb8ea00	Airport Security Protection	unit_security	2026-07-01 10:34:52.650924	\N	Aktif
12	Non-Airline	8421d4c80e3ff705a3535dd33bd6ff97	User Non-Airline	ground_handling	2026-07-28 11:54:26	7	Aktif
1	admin	0e7517141fb53f21ee439b355b5a1d0a	Inggrid Adellia	admin	2026-06-24 11:43:41.839916	\N	Aktif
3	unit_equipment	8a09c927af9b4988ea77023badc0b356	Nabil Albuqari	unit_equipment	2026-06-24 11:43:41.839916	\N	Aktif
4	unit_sales	538cbce4c5e5b8d6d64751094673ba94	Nama Operator Sales	unit_sales	2026-06-24 11:43:41.839916	\N	Aktif
5	gh_garuda	44f9f897e9a9a1a07c0cc2c0fc116d91	GH Garuda Indonesia	ground_handling	2026-06-24 15:16:00.943509	1	Aktif
8	gh_citilink	b82028369977b0ac42f1e2290d337495	Nama GH Citilink	ground_handling	2026-06-25 14:05:21.85248	3	Aktif
9	gh_lion2	5f8e46decce2618736d32361c0989f8c	INGGRID ADELLIA	ground_handling	2026-06-25 09:02:56	2	Aktif
10	admin2	4c0325984157022019429ab0674a0664	Nama Admin 2	admin	2026-06-27 16:21:57	\N	Aktif
14	gh_pelitaair	571b0b3e379e54189828deb9513d1475	Ivander Rombelinggi'	ground_handling	2026-07-29 05:44:50	6	Aktif
6	gh_lion	748f27144e162b69fa5bba0dcf954e3a	GH Lion Air	ground_handling	2026-06-24 15:16:00.943509	2	Aktif
\.


--
-- TOC entry 5210 (class 0 OID 0)
-- Dependencies: 220
-- Name: activity_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.activity_log_id_seq', 853, true);


--
-- TOC entry 5211 (class 0 OID 0)
-- Dependencies: 222
-- Name: airlines_id_airline_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.airlines_id_airline_seq', 7, true);


--
-- TOC entry 5212 (class 0 OID 0)
-- Dependencies: 224
-- Name: blocked_ip_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.blocked_ip_id_seq', 1, false);


--
-- TOC entry 5213 (class 0 OID 0)
-- Dependencies: 226
-- Name: detail_permohonan_keluar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.detail_permohonan_keluar_id_seq', 1, true);


--
-- TOC entry 5214 (class 0 OID 0)
-- Dependencies: 229
-- Name: gse_id_gse_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gse_id_gse_seq', 3, true);


--
-- TOC entry 5215 (class 0 OID 0)
-- Dependencies: 231
-- Name: ids_ancaman_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ids_ancaman_id_seq', 5, true);


--
-- TOC entry 5216 (class 0 OID 0)
-- Dependencies: 233
-- Name: login_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.login_log_id_seq', 372, true);


--
-- TOC entry 5217 (class 0 OID 0)
-- Dependencies: 235
-- Name: permohonan_keluar_id_permohonan_keluar_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permohonan_keluar_id_permohonan_keluar_seq', 1, true);


--
-- TOC entry 5218 (class 0 OID 0)
-- Dependencies: 237
-- Name: permohonan_masuk_gse_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permohonan_masuk_gse_id_seq', 7, true);


--
-- TOC entry 5219 (class 0 OID 0)
-- Dependencies: 238
-- Name: permohonan_masuk_id_permohonan_masuk_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permohonan_masuk_id_permohonan_masuk_seq', 3, true);


--
-- TOC entry 5220 (class 0 OID 0)
-- Dependencies: 240
-- Name: riwayat_verifikasi_gse_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.riwayat_verifikasi_gse_id_seq', 84, true);


--
-- TOC entry 5221 (class 0 OID 0)
-- Dependencies: 242
-- Name: users_id_user_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_user_seq', 14, true);


--
-- TOC entry 4968 (class 2606 OID 33579)
-- Name: activity_log activity_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT activity_log_pkey PRIMARY KEY (id);


--
-- TOC entry 4970 (class 2606 OID 33581)
-- Name: airlines airlines_kode_airline_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.airlines
    ADD CONSTRAINT airlines_kode_airline_key UNIQUE (kode_airline);


--
-- TOC entry 4972 (class 2606 OID 33583)
-- Name: airlines airlines_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.airlines
    ADD CONSTRAINT airlines_pkey PRIMARY KEY (id_airline);


--
-- TOC entry 4974 (class 2606 OID 33585)
-- Name: blocked_ip blocked_ip_ip_address_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blocked_ip
    ADD CONSTRAINT blocked_ip_ip_address_key UNIQUE (ip_address);


--
-- TOC entry 4976 (class 2606 OID 33587)
-- Name: blocked_ip blocked_ip_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blocked_ip
    ADD CONSTRAINT blocked_ip_pkey PRIMARY KEY (id);


--
-- TOC entry 4978 (class 2606 OID 33589)
-- Name: detail_permohonan_keluar detail_permohonan_keluar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_permohonan_keluar
    ADD CONSTRAINT detail_permohonan_keluar_pkey PRIMARY KEY (id);


--
-- TOC entry 4984 (class 2606 OID 33591)
-- Name: gse gse_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gse
    ADD CONSTRAINT gse_pkey PRIMARY KEY (id_gse);


--
-- TOC entry 4986 (class 2606 OID 33593)
-- Name: ids_ancaman ids_ancaman_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ids_ancaman
    ADD CONSTRAINT ids_ancaman_pkey PRIMARY KEY (id);


--
-- TOC entry 4988 (class 2606 OID 33595)
-- Name: login_log login_log_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.login_log
    ADD CONSTRAINT login_log_pkey PRIMARY KEY (id);


--
-- TOC entry 4990 (class 2606 OID 33597)
-- Name: permohonan_keluar permohonan_keluar_nomor_permohonan_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar
    ADD CONSTRAINT permohonan_keluar_nomor_permohonan_key UNIQUE (nomor_permohonan);


--
-- TOC entry 4992 (class 2606 OID 33599)
-- Name: permohonan_keluar permohonan_keluar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar
    ADD CONSTRAINT permohonan_keluar_pkey PRIMARY KEY (id_permohonan_keluar);


--
-- TOC entry 4982 (class 2606 OID 33601)
-- Name: detail_permohonan_masuk permohonan_masuk_gse_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_gse_pkey PRIMARY KEY (id);


--
-- TOC entry 4994 (class 2606 OID 33603)
-- Name: permohonan_masuk permohonan_masuk_nomor_permohonan_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_nomor_permohonan_key UNIQUE (nomor_permohonan);


--
-- TOC entry 4996 (class 2606 OID 33605)
-- Name: permohonan_masuk permohonan_masuk_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_pkey PRIMARY KEY (id_permohonan_masuk);


--
-- TOC entry 4999 (class 2606 OID 33607)
-- Name: riwayat_verifikasi_gse riwayat_verifikasi_gse_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.riwayat_verifikasi_gse
    ADD CONSTRAINT riwayat_verifikasi_gse_pkey PRIMARY KEY (id);


--
-- TOC entry 4980 (class 2606 OID 33762)
-- Name: detail_permohonan_keluar uq_permohonan_keluar_no_asset; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_permohonan_keluar
    ADD CONSTRAINT uq_permohonan_keluar_no_asset UNIQUE (id_permohonan_keluar, no_asset);


--
-- TOC entry 5001 (class 2606 OID 33609)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id_user);


--
-- TOC entry 5003 (class 2606 OID 33611)
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- TOC entry 4997 (class 1259 OID 33612)
-- Name: idx_riwayat_verifikasi_gse_detail; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_riwayat_verifikasi_gse_detail ON public.riwayat_verifikasi_gse USING btree (id_detail);


--
-- TOC entry 5005 (class 2606 OID 33613)
-- Name: detail_permohonan_masuk detail_permohonan_masuk_diproses_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_permohonan_masuk
    ADD CONSTRAINT detail_permohonan_masuk_diproses_oleh_fkey FOREIGN KEY (diproses_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5004 (class 2606 OID 33618)
-- Name: detail_permohonan_keluar fk_permohonan_keluar; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_permohonan_keluar
    ADD CONSTRAINT fk_permohonan_keluar FOREIGN KEY (id_permohonan_keluar) REFERENCES public.permohonan_keluar(id_permohonan_keluar) ON DELETE CASCADE;


--
-- TOC entry 5014 (class 2606 OID 33623)
-- Name: permohonan_masuk fk_pm_pk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT fk_pm_pk FOREIGN KEY (id_permohonan_keluar) REFERENCES public.permohonan_keluar(id_permohonan_keluar);


--
-- TOC entry 5007 (class 2606 OID 33628)
-- Name: gse gse_id_airline_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gse
    ADD CONSTRAINT gse_id_airline_fkey FOREIGN KEY (id_airline) REFERENCES public.airlines(id_airline);


--
-- TOC entry 5008 (class 2606 OID 33633)
-- Name: permohonan_keluar permohonan_keluar_created_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar
    ADD CONSTRAINT permohonan_keluar_created_by_fkey FOREIGN KEY (created_by) REFERENCES public.users(id_user);


--
-- TOC entry 5009 (class 2606 OID 33638)
-- Name: permohonan_keluar permohonan_keluar_id_airline_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar
    ADD CONSTRAINT permohonan_keluar_id_airline_fkey FOREIGN KEY (id_airline) REFERENCES public.airlines(id_airline);


--
-- TOC entry 5010 (class 2606 OID 33643)
-- Name: permohonan_keluar permohonan_keluar_verifikasi_equipment_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar
    ADD CONSTRAINT permohonan_keluar_verifikasi_equipment_oleh_fkey FOREIGN KEY (verifikasi_equipment_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5011 (class 2606 OID 33648)
-- Name: permohonan_keluar permohonan_keluar_verifikasi_operasional_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar
    ADD CONSTRAINT permohonan_keluar_verifikasi_operasional_oleh_fkey FOREIGN KEY (verifikasi_operasi_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5012 (class 2606 OID 33653)
-- Name: permohonan_keluar permohonan_keluar_verifikasi_sales_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar
    ADD CONSTRAINT permohonan_keluar_verifikasi_sales_oleh_fkey FOREIGN KEY (verifikasi_sales_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5013 (class 2606 OID 33658)
-- Name: permohonan_keluar permohonan_keluar_verifikasi_security_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_keluar
    ADD CONSTRAINT permohonan_keluar_verifikasi_security_oleh_fkey FOREIGN KEY (verifikasi_security_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5015 (class 2606 OID 33663)
-- Name: permohonan_masuk permohonan_masuk_created_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_created_by_fkey FOREIGN KEY (created_by) REFERENCES public.users(id_user);


--
-- TOC entry 5006 (class 2606 OID 33668)
-- Name: detail_permohonan_masuk permohonan_masuk_gse_id_permohonan_masuk_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detail_permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_gse_id_permohonan_masuk_fkey FOREIGN KEY (id_permohonan_masuk) REFERENCES public.permohonan_masuk(id_permohonan_masuk) ON DELETE CASCADE;


--
-- TOC entry 5016 (class 2606 OID 33673)
-- Name: permohonan_masuk permohonan_masuk_id_airline_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_id_airline_fkey FOREIGN KEY (id_airline) REFERENCES public.airlines(id_airline) ON DELETE SET NULL;


--
-- TOC entry 5017 (class 2606 OID 33678)
-- Name: permohonan_masuk permohonan_masuk_verifikasi_equipment_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_verifikasi_equipment_oleh_fkey FOREIGN KEY (verifikasi_equipment_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5018 (class 2606 OID 33683)
-- Name: permohonan_masuk permohonan_masuk_verifikasi_operasional_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_verifikasi_operasional_oleh_fkey FOREIGN KEY (verifikasi_operasi_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5019 (class 2606 OID 33688)
-- Name: permohonan_masuk permohonan_masuk_verifikasi_sales_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_verifikasi_sales_oleh_fkey FOREIGN KEY (verifikasi_sales_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5020 (class 2606 OID 33693)
-- Name: permohonan_masuk permohonan_masuk_verifikasi_security_oleh_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permohonan_masuk
    ADD CONSTRAINT permohonan_masuk_verifikasi_security_oleh_fkey FOREIGN KEY (verifikasi_security_oleh) REFERENCES public.users(id_user);


--
-- TOC entry 5021 (class 2606 OID 33698)
-- Name: users users_id_airline_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_airline_fkey FOREIGN KEY (id_airline) REFERENCES public.airlines(id_airline) ON DELETE SET NULL;


-- Completed on 2026-08-19 14:37:59

--
-- PostgreSQL database dump complete
--

\unrestrict 1x9E6WdZ2Cv2INfVnaPuPeMXa1vQ2RClxQcaXKU3fpVKgEHInOtOzdMmdcQ35Rk

