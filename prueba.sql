--
-- PostgreSQL database dump
--

-- Dumped from database version 17.3
-- Dumped by pg_dump version 17.2

-- Started on 2025-03-14 08:05:32

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

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- TOC entry 5124 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 217 (class 1259 OID 17108)
-- Name: almacenes_minerales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.almacenes_minerales (
    id integer NOT NULL,
    nombre text NOT NULL
);


ALTER TABLE public.almacenes_minerales OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 17113)
-- Name: almacenes_minerales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.almacenes_minerales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.almacenes_minerales_id_seq OWNER TO postgres;

--
-- TOC entry 5125 (class 0 OID 0)
-- Dependencies: 218
-- Name: almacenes_minerales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.almacenes_minerales_id_seq OWNED BY public.almacenes_minerales.id;


--
-- TOC entry 219 (class 1259 OID 17114)
-- Name: camiones_pzo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.camiones_pzo (
    id integer NOT NULL,
    id_tipo_mineral integer,
    id_empresa_d integer,
    cant_camiones integer,
    total_toneladas integer,
    fecha date,
    comentario text
);


ALTER TABLE public.camiones_pzo OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 17119)
-- Name: camiones_pzo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.camiones_pzo_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.camiones_pzo_id_seq OWNER TO postgres;

--
-- TOC entry 5126 (class 0 OID 0)
-- Dependencies: 220
-- Name: camiones_pzo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.camiones_pzo_id_seq OWNED BY public.camiones_pzo.id;


--
-- TOC entry 221 (class 1259 OID 17120)
-- Name: cantidad_gondolas_fg; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cantidad_gondolas_fg (
    id integer NOT NULL,
    id_gondola integer,
    cantidad_gon integer,
    id_empresa integer,
    id_planta integer,
    id_tipo_carga integer,
    total_carga integer,
    fecha date,
    comentario text
);


ALTER TABLE public.cantidad_gondolas_fg OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 17125)
-- Name: cantidad_gondolas_fg_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cantidad_gondolas_fg_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cantidad_gondolas_fg_id_seq OWNER TO postgres;

--
-- TOC entry 5127 (class 0 OID 0)
-- Dependencies: 222
-- Name: cantidad_gondolas_fg_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cantidad_gondolas_fg_id_seq OWNED BY public.cantidad_gondolas_fg.id;


--
-- TOC entry 223 (class 1259 OID 17126)
-- Name: cantidad_gondolas_teu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cantidad_gondolas_teu (
    id integer NOT NULL,
    id_gondola integer,
    cantidad_gon integer,
    id_empresa integer,
    id_mina integer,
    id_tipo_carga integer,
    total_carga integer,
    fecha date,
    comentario text
);


ALTER TABLE public.cantidad_gondolas_teu OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 17131)
-- Name: cantidad_gondolas_teu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cantidad_gondolas_teu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cantidad_gondolas_teu_id_seq OWNER TO postgres;

--
-- TOC entry 5128 (class 0 OID 0)
-- Dependencies: 224
-- Name: cantidad_gondolas_teu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cantidad_gondolas_teu_id_seq OWNED BY public.cantidad_gondolas_teu.id;


--
-- TOC entry 225 (class 1259 OID 17132)
-- Name: cantidad_tolvas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cantidad_tolvas (
    id integer NOT NULL,
    id_tolva integer,
    cantidad integer,
    id_planta integer,
    id_tipo_carga integer,
    total_carga integer,
    fecha date,
    comentario text,
    id_empresa integer
);


ALTER TABLE public.cantidad_tolvas OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 17137)
-- Name: cantidad_tolvas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cantidad_tolvas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cantidad_tolvas_id_seq OWNER TO postgres;

--
-- TOC entry 5129 (class 0 OID 0)
-- Dependencies: 226
-- Name: cantidad_tolvas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cantidad_tolvas_id_seq OWNED BY public.cantidad_tolvas.id;


--
-- TOC entry 227 (class 1259 OID 17138)
-- Name: carga_barco_pzo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carga_barco_pzo (
    id integer NOT NULL,
    id_mineral integer,
    cantidad integer,
    nombre_barco text,
    num_embarque integer,
    destino text,
    id_empresa integer,
    exportacion boolean,
    fecha date,
    comentario text
);


ALTER TABLE public.carga_barco_pzo OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 17143)
-- Name: carga_barco_pzo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carga_barco_pzo_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carga_barco_pzo_id_seq OWNER TO postgres;

--
-- TOC entry 5130 (class 0 OID 0)
-- Dependencies: 228
-- Name: carga_barco_pzo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carga_barco_pzo_id_seq OWNED BY public.carga_barco_pzo.id;


--
-- TOC entry 229 (class 1259 OID 17144)
-- Name: carga_de_vagones_cd_piar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carga_de_vagones_cd_piar (
    id integer NOT NULL,
    id_cantidad_gon_teu integer,
    id_cantidad_gon_fg integer,
    id_cantidad_tol integer
);


ALTER TABLE public.carga_de_vagones_cd_piar OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 17147)
-- Name: carga_de_vagones_cd_piar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carga_de_vagones_cd_piar_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carga_de_vagones_cd_piar_id_seq OWNER TO postgres;

--
-- TOC entry 5131 (class 0 OID 0)
-- Dependencies: 230
-- Name: carga_de_vagones_cd_piar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carga_de_vagones_cd_piar_id_seq OWNED BY public.carga_de_vagones_cd_piar.id;


--
-- TOC entry 231 (class 1259 OID 17148)
-- Name: despacho_nacional; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.despacho_nacional (
    id integer NOT NULL,
    id_tipo_mineral integer,
    id_empresa_d integer,
    cant_vagones integer,
    total_toneladas integer,
    fecha date,
    comentario text
);


ALTER TABLE public.despacho_nacional OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 17153)
-- Name: despacho_nacional_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.despacho_nacional_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.despacho_nacional_id_seq OWNER TO postgres;

--
-- TOC entry 5132 (class 0 OID 0)
-- Dependencies: 232
-- Name: despacho_nacional_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.despacho_nacional_id_seq OWNED BY public.despacho_nacional.id;


--
-- TOC entry 233 (class 1259 OID 17154)
-- Name: empresa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.empresa (
    id integer NOT NULL,
    nombre text NOT NULL
);


ALTER TABLE public.empresa OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 17159)
-- Name: empresa_extranjera; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.empresa_extranjera (
    id integer NOT NULL,
    nombre text NOT NULL
);


ALTER TABLE public.empresa_extranjera OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 17164)
-- Name: empresa_extranjera_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.empresa_extranjera_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.empresa_extranjera_id_seq OWNER TO postgres;

--
-- TOC entry 5133 (class 0 OID 0)
-- Dependencies: 235
-- Name: empresa_extranjera_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.empresa_extranjera_id_seq OWNED BY public.empresa_extranjera.id;


--
-- TOC entry 236 (class 1259 OID 17165)
-- Name: empresa_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.empresa_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.empresa_id_seq OWNER TO postgres;

--
-- TOC entry 5134 (class 0 OID 0)
-- Dependencies: 236
-- Name: empresa_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.empresa_id_seq OWNED BY public.empresa.id;


--
-- TOC entry 237 (class 1259 OID 17166)
-- Name: excavacion_cd_piar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.excavacion_cd_piar (
    id integer NOT NULL,
    id_tipo_material integer,
    cantidad integer,
    id_mina integer,
    id_empresa integer,
    fecha date,
    comentario text
);


ALTER TABLE public.excavacion_cd_piar OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 17171)
-- Name: excavacion_cd_piar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.excavacion_cd_piar_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.excavacion_cd_piar_id_seq OWNER TO postgres;

--
-- TOC entry 5135 (class 0 OID 0)
-- Dependencies: 238
-- Name: excavacion_cd_piar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.excavacion_cd_piar_id_seq OWNED BY public.excavacion_cd_piar.id;


--
-- TOC entry 239 (class 1259 OID 17172)
-- Name: inventario_cd_piar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.inventario_cd_piar (
    id integer NOT NULL,
    id_m_excavado integer,
    id_m_procesado integer
);


ALTER TABLE public.inventario_cd_piar OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 17175)
-- Name: inventario_cd_piar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.inventario_cd_piar_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.inventario_cd_piar_id_seq OWNER TO postgres;

--
-- TOC entry 5136 (class 0 OID 0)
-- Dependencies: 240
-- Name: inventario_cd_piar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.inventario_cd_piar_id_seq OWNED BY public.inventario_cd_piar.id;


--
-- TOC entry 241 (class 1259 OID 17176)
-- Name: inventario_exc_cd_piar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.inventario_exc_cd_piar (
    id integer NOT NULL,
    id_tipo integer,
    id_mina integer,
    cantidad integer,
    fecha date,
    comentario text
);


ALTER TABLE public.inventario_exc_cd_piar OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 17181)
-- Name: inventario_exc_cd_piar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.inventario_exc_cd_piar_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.inventario_exc_cd_piar_id_seq OWNER TO postgres;

--
-- TOC entry 5137 (class 0 OID 0)
-- Dependencies: 242
-- Name: inventario_exc_cd_piar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.inventario_exc_cd_piar_id_seq OWNED BY public.inventario_exc_cd_piar.id;


--
-- TOC entry 243 (class 1259 OID 17182)
-- Name: inventario_minerales_pzo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.inventario_minerales_pzo (
    id integer NOT NULL,
    id_tipo_mineral integer,
    cantidad integer,
    id_ubicacion integer,
    fecha date,
    comentario text
);


ALTER TABLE public.inventario_minerales_pzo OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 17187)
-- Name: inventario_minerales_pzo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.inventario_minerales_pzo_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.inventario_minerales_pzo_id_seq OWNER TO postgres;

--
-- TOC entry 5138 (class 0 OID 0)
-- Dependencies: 244
-- Name: inventario_minerales_pzo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.inventario_minerales_pzo_id_seq OWNED BY public.inventario_minerales_pzo.id;


--
-- TOC entry 245 (class 1259 OID 17188)
-- Name: inventario_proc_cd_piar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.inventario_proc_cd_piar (
    id integer NOT NULL,
    id_planta integer,
    id_tipo integer,
    cantidad integer,
    fecha date,
    comentario text
);


ALTER TABLE public.inventario_proc_cd_piar OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 17193)
-- Name: inventario_proc_cd_piar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.inventario_proc_cd_piar_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.inventario_proc_cd_piar_id_seq OWNER TO postgres;

--
-- TOC entry 5139 (class 0 OID 0)
-- Dependencies: 246
-- Name: inventario_proc_cd_piar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.inventario_proc_cd_piar_id_seq OWNED BY public.inventario_proc_cd_piar.id;


--
-- TOC entry 247 (class 1259 OID 17194)
-- Name: inventarios_preproducidos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.inventarios_preproducidos (
    id integer NOT NULL,
    id_producto integer,
    cantidad_prod integer,
    id_subproducto integer,
    cantidad_sub integer,
    fecha date,
    comentario text
);


ALTER TABLE public.inventarios_preproducidos OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 17199)
-- Name: inventarios_preproducidos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.inventarios_preproducidos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.inventarios_preproducidos_id_seq OWNER TO postgres;

--
-- TOC entry 5140 (class 0 OID 0)
-- Dependencies: 248
-- Name: inventarios_preproducidos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.inventarios_preproducidos_id_seq OWNED BY public.inventarios_preproducidos.id;


--
-- TOC entry 249 (class 1259 OID 17200)
-- Name: material_excavado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.material_excavado (
    id integer NOT NULL,
    tipo text NOT NULL
);


ALTER TABLE public.material_excavado OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 17205)
-- Name: material_excavado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.material_excavado_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.material_excavado_id_seq OWNER TO postgres;

--
-- TOC entry 5141 (class 0 OID 0)
-- Dependencies: 250
-- Name: material_excavado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.material_excavado_id_seq OWNED BY public.material_excavado.id;


--
-- TOC entry 251 (class 1259 OID 17206)
-- Name: minas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.minas (
    id integer NOT NULL,
    nombre text NOT NULL
);


ALTER TABLE public.minas OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 17211)
-- Name: minas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.minas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.minas_id_seq OWNER TO postgres;

--
-- TOC entry 5142 (class 0 OID 0)
-- Dependencies: 252
-- Name: minas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.minas_id_seq OWNED BY public.minas.id;


--
-- TOC entry 253 (class 1259 OID 17212)
-- Name: mineral_procesado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mineral_procesado (
    id integer NOT NULL,
    tipo text NOT NULL
);


ALTER TABLE public.mineral_procesado OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 17217)
-- Name: mineral_procesado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mineral_procesado_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mineral_procesado_id_seq OWNER TO postgres;

--
-- TOC entry 5143 (class 0 OID 0)
-- Dependencies: 254
-- Name: mineral_procesado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mineral_procesado_id_seq OWNED BY public.mineral_procesado.id;


--
-- TOC entry 255 (class 1259 OID 17218)
-- Name: operaciones_siderurgicas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.operaciones_siderurgicas (
    id integer NOT NULL,
    cantidad_prod integer,
    fecha date,
    comentario text,
    id_planta_s integer
);


ALTER TABLE public.operaciones_siderurgicas OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 17223)
-- Name: operaciones_siderurgicas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.operaciones_siderurgicas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.operaciones_siderurgicas_id_seq OWNER TO postgres;

--
-- TOC entry 5144 (class 0 OID 0)
-- Dependencies: 256
-- Name: operaciones_siderurgicas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.operaciones_siderurgicas_id_seq OWNED BY public.operaciones_siderurgicas.id;


--
-- TOC entry 257 (class 1259 OID 17224)
-- Name: planta; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.planta (
    id integer NOT NULL,
    nombre text NOT NULL
);


ALTER TABLE public.planta OWNER TO postgres;

--
-- TOC entry 258 (class 1259 OID 17229)
-- Name: planta_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.planta_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.planta_id_seq OWNER TO postgres;

--
-- TOC entry 5145 (class 0 OID 0)
-- Dependencies: 258
-- Name: planta_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.planta_id_seq OWNED BY public.planta.id;


--
-- TOC entry 259 (class 1259 OID 17230)
-- Name: plantas_siderurgicas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.plantas_siderurgicas (
    id integer NOT NULL,
    nombre text
);


ALTER TABLE public.plantas_siderurgicas OWNER TO postgres;

--
-- TOC entry 260 (class 1259 OID 17235)
-- Name: plantas_siderurgicas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.plantas_siderurgicas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.plantas_siderurgicas_id_seq OWNER TO postgres;

--
-- TOC entry 5146 (class 0 OID 0)
-- Dependencies: 260
-- Name: plantas_siderurgicas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.plantas_siderurgicas_id_seq OWNED BY public.plantas_siderurgicas.id;


--
-- TOC entry 261 (class 1259 OID 17236)
-- Name: procesamiento_pt_cd_piar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.procesamiento_pt_cd_piar (
    id integer NOT NULL,
    id_planta integer,
    id_empresa integer,
    id_mineral_proc integer,
    cantidad integer,
    fecha date,
    comentario text
);


ALTER TABLE public.procesamiento_pt_cd_piar OWNER TO postgres;

--
-- TOC entry 262 (class 1259 OID 17241)
-- Name: procesamiento_pt_cd_piar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.procesamiento_pt_cd_piar_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.procesamiento_pt_cd_piar_id_seq OWNER TO postgres;

--
-- TOC entry 5147 (class 0 OID 0)
-- Dependencies: 262
-- Name: procesamiento_pt_cd_piar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.procesamiento_pt_cd_piar_id_seq OWNED BY public.procesamiento_pt_cd_piar.id;


--
-- TOC entry 263 (class 1259 OID 17242)
-- Name: produccion_total_pzo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.produccion_total_pzo (
    id integer NOT NULL,
    cant_volteo integer,
    cant_vaciado integer,
    cant_fino integer,
    cant_grueso integer,
    total_producido integer,
    fecha date,
    comentario text
);


ALTER TABLE public.produccion_total_pzo OWNER TO postgres;

--
-- TOC entry 264 (class 1259 OID 17247)
-- Name: produccion_total_pzo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.produccion_total_pzo_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.produccion_total_pzo_id_seq OWNER TO postgres;

--
-- TOC entry 5148 (class 0 OID 0)
-- Dependencies: 264
-- Name: produccion_total_pzo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.produccion_total_pzo_id_seq OWNED BY public.produccion_total_pzo.id;


--
-- TOC entry 265 (class 1259 OID 17248)
-- Name: productos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.productos (
    id integer NOT NULL,
    nombre text NOT NULL
);


ALTER TABLE public.productos OWNER TO postgres;

--
-- TOC entry 266 (class 1259 OID 17253)
-- Name: productos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.productos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.productos_id_seq OWNER TO postgres;

--
-- TOC entry 5149 (class 0 OID 0)
-- Dependencies: 266
-- Name: productos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.productos_id_seq OWNED BY public.productos.id;


--
-- TOC entry 267 (class 1259 OID 17254)
-- Name: subproductos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.subproductos (
    id integer NOT NULL,
    nombre text NOT NULL
);


ALTER TABLE public.subproductos OWNER TO postgres;

--
-- TOC entry 268 (class 1259 OID 17259)
-- Name: subproductos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.subproductos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.subproductos_id_seq OWNER TO postgres;

--
-- TOC entry 5150 (class 0 OID 0)
-- Dependencies: 268
-- Name: subproductos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.subproductos_id_seq OWNED BY public.subproductos.id;


--
-- TOC entry 269 (class 1259 OID 17260)
-- Name: tipos_de_minerales_pzo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipos_de_minerales_pzo (
    id integer NOT NULL,
    nombre text NOT NULL
);


ALTER TABLE public.tipos_de_minerales_pzo OWNER TO postgres;

--
-- TOC entry 270 (class 1259 OID 17265)
-- Name: tipos_de_minerales_pzo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipos_de_minerales_pzo_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tipos_de_minerales_pzo_id_seq OWNER TO postgres;

--
-- TOC entry 5151 (class 0 OID 0)
-- Dependencies: 270
-- Name: tipos_de_minerales_pzo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipos_de_minerales_pzo_id_seq OWNED BY public.tipos_de_minerales_pzo.id;


--
-- TOC entry 271 (class 1259 OID 17266)
-- Name: transporte_linea_principal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.transporte_linea_principal (
    id integer NOT NULL,
    cant_vagones integer,
    cant_gondolas integer,
    cant_tolvas integer,
    cant_trenes_p integer,
    cant_trenes_e integer,
    cant_trenes_a integer,
    fecha date,
    comentario text,
    total integer
);


ALTER TABLE public.transporte_linea_principal OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 17271)
-- Name: transporte_linea_principal_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.transporte_linea_principal_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.transporte_linea_principal_id_seq OWNER TO postgres;

--
-- TOC entry 5152 (class 0 OID 0)
-- Dependencies: 272
-- Name: transporte_linea_principal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.transporte_linea_principal_id_seq OWNED BY public.transporte_linea_principal.id;


--
-- TOC entry 273 (class 1259 OID 17272)
-- Name: vagones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.vagones (
    id integer NOT NULL,
    tipo text NOT NULL,
    capacidad integer
);


ALTER TABLE public.vagones OWNER TO postgres;

--
-- TOC entry 274 (class 1259 OID 17277)
-- Name: vagones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.vagones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.vagones_id_seq OWNER TO postgres;

--
-- TOC entry 5153 (class 0 OID 0)
-- Dependencies: 274
-- Name: vagones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.vagones_id_seq OWNED BY public.vagones.id;


--
-- TOC entry 276 (class 1259 OID 25245)
-- Name: zf_usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.zf_usuarios (
    id integer NOT NULL,
    nombre text
);


ALTER TABLE public.zf_usuarios OWNER TO postgres;

--
-- TOC entry 275 (class 1259 OID 25244)
-- Name: zf_usuarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.zf_usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.zf_usuarios_id_seq OWNER TO postgres;

--
-- TOC entry 5154 (class 0 OID 0)
-- Dependencies: 275
-- Name: zf_usuarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.zf_usuarios_id_seq OWNED BY public.zf_usuarios.id;


--
-- TOC entry 4786 (class 2604 OID 25253)
-- Name: almacenes_minerales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.almacenes_minerales ALTER COLUMN id SET DEFAULT nextval('public.almacenes_minerales_id_seq'::regclass);


--
-- TOC entry 4787 (class 2604 OID 25254)
-- Name: camiones_pzo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.camiones_pzo ALTER COLUMN id SET DEFAULT nextval('public.camiones_pzo_id_seq'::regclass);


--
-- TOC entry 4788 (class 2604 OID 25255)
-- Name: cantidad_gondolas_fg id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_fg ALTER COLUMN id SET DEFAULT nextval('public.cantidad_gondolas_fg_id_seq'::regclass);


--
-- TOC entry 4789 (class 2604 OID 25256)
-- Name: cantidad_gondolas_teu id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_teu ALTER COLUMN id SET DEFAULT nextval('public.cantidad_gondolas_teu_id_seq'::regclass);


--
-- TOC entry 4790 (class 2604 OID 25257)
-- Name: cantidad_tolvas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_tolvas ALTER COLUMN id SET DEFAULT nextval('public.cantidad_tolvas_id_seq'::regclass);


--
-- TOC entry 4791 (class 2604 OID 25258)
-- Name: carga_barco_pzo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_barco_pzo ALTER COLUMN id SET DEFAULT nextval('public.carga_barco_pzo_id_seq'::regclass);


--
-- TOC entry 4792 (class 2604 OID 25259)
-- Name: carga_de_vagones_cd_piar id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_de_vagones_cd_piar ALTER COLUMN id SET DEFAULT nextval('public.carga_de_vagones_cd_piar_id_seq'::regclass);


--
-- TOC entry 4793 (class 2604 OID 25260)
-- Name: despacho_nacional id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.despacho_nacional ALTER COLUMN id SET DEFAULT nextval('public.despacho_nacional_id_seq'::regclass);


--
-- TOC entry 4794 (class 2604 OID 25261)
-- Name: empresa id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresa ALTER COLUMN id SET DEFAULT nextval('public.empresa_id_seq'::regclass);


--
-- TOC entry 4795 (class 2604 OID 25262)
-- Name: empresa_extranjera id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresa_extranjera ALTER COLUMN id SET DEFAULT nextval('public.empresa_extranjera_id_seq'::regclass);


--
-- TOC entry 4796 (class 2604 OID 25263)
-- Name: excavacion_cd_piar id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.excavacion_cd_piar ALTER COLUMN id SET DEFAULT nextval('public.excavacion_cd_piar_id_seq'::regclass);


--
-- TOC entry 4797 (class 2604 OID 25264)
-- Name: inventario_cd_piar id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_cd_piar ALTER COLUMN id SET DEFAULT nextval('public.inventario_cd_piar_id_seq'::regclass);


--
-- TOC entry 4798 (class 2604 OID 25265)
-- Name: inventario_exc_cd_piar id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_exc_cd_piar ALTER COLUMN id SET DEFAULT nextval('public.inventario_exc_cd_piar_id_seq'::regclass);


--
-- TOC entry 4799 (class 2604 OID 25266)
-- Name: inventario_minerales_pzo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_minerales_pzo ALTER COLUMN id SET DEFAULT nextval('public.inventario_minerales_pzo_id_seq'::regclass);


--
-- TOC entry 4800 (class 2604 OID 25267)
-- Name: inventario_proc_cd_piar id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_proc_cd_piar ALTER COLUMN id SET DEFAULT nextval('public.inventario_proc_cd_piar_id_seq'::regclass);


--
-- TOC entry 4801 (class 2604 OID 25268)
-- Name: inventarios_preproducidos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventarios_preproducidos ALTER COLUMN id SET DEFAULT nextval('public.inventarios_preproducidos_id_seq'::regclass);


--
-- TOC entry 4802 (class 2604 OID 25269)
-- Name: material_excavado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.material_excavado ALTER COLUMN id SET DEFAULT nextval('public.material_excavado_id_seq'::regclass);


--
-- TOC entry 4803 (class 2604 OID 25270)
-- Name: minas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.minas ALTER COLUMN id SET DEFAULT nextval('public.minas_id_seq'::regclass);


--
-- TOC entry 4804 (class 2604 OID 25271)
-- Name: mineral_procesado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mineral_procesado ALTER COLUMN id SET DEFAULT nextval('public.mineral_procesado_id_seq'::regclass);


--
-- TOC entry 4805 (class 2604 OID 25272)
-- Name: operaciones_siderurgicas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.operaciones_siderurgicas ALTER COLUMN id SET DEFAULT nextval('public.operaciones_siderurgicas_id_seq'::regclass);


--
-- TOC entry 4806 (class 2604 OID 25273)
-- Name: planta id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planta ALTER COLUMN id SET DEFAULT nextval('public.planta_id_seq'::regclass);


--
-- TOC entry 4807 (class 2604 OID 25274)
-- Name: plantas_siderurgicas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plantas_siderurgicas ALTER COLUMN id SET DEFAULT nextval('public.plantas_siderurgicas_id_seq'::regclass);


--
-- TOC entry 4808 (class 2604 OID 25275)
-- Name: procesamiento_pt_cd_piar id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.procesamiento_pt_cd_piar ALTER COLUMN id SET DEFAULT nextval('public.procesamiento_pt_cd_piar_id_seq'::regclass);


--
-- TOC entry 4809 (class 2604 OID 25276)
-- Name: produccion_total_pzo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_total_pzo ALTER COLUMN id SET DEFAULT nextval('public.produccion_total_pzo_id_seq'::regclass);


--
-- TOC entry 4810 (class 2604 OID 25277)
-- Name: productos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos ALTER COLUMN id SET DEFAULT nextval('public.productos_id_seq'::regclass);


--
-- TOC entry 4811 (class 2604 OID 25278)
-- Name: subproductos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.subproductos ALTER COLUMN id SET DEFAULT nextval('public.subproductos_id_seq'::regclass);


--
-- TOC entry 4812 (class 2604 OID 25279)
-- Name: tipos_de_minerales_pzo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipos_de_minerales_pzo ALTER COLUMN id SET DEFAULT nextval('public.tipos_de_minerales_pzo_id_seq'::regclass);


--
-- TOC entry 4813 (class 2604 OID 25280)
-- Name: transporte_linea_principal id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transporte_linea_principal ALTER COLUMN id SET DEFAULT nextval('public.transporte_linea_principal_id_seq'::regclass);


--
-- TOC entry 4814 (class 2604 OID 25281)
-- Name: vagones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vagones ALTER COLUMN id SET DEFAULT nextval('public.vagones_id_seq'::regclass);


--
-- TOC entry 4815 (class 2604 OID 25248)
-- Name: zf_usuarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.zf_usuarios ALTER COLUMN id SET DEFAULT nextval('public.zf_usuarios_id_seq'::regclass);


--
-- TOC entry 5059 (class 0 OID 17108)
-- Dependencies: 217
-- Data for Name: almacenes_minerales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.almacenes_minerales (id, nombre) FROM stdin;
1	Almacen 1
2	Almacen 2
\.


--
-- TOC entry 5061 (class 0 OID 17114)
-- Dependencies: 219
-- Data for Name: camiones_pzo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.camiones_pzo (id, id_tipo_mineral, id_empresa_d, cant_camiones, total_toneladas, fecha, comentario) FROM stdin;
1	2	2	12	600	2025-03-02	Probando
2	2	5	10	500	2025-03-04	Probando
\.


--
-- TOC entry 5063 (class 0 OID 17120)
-- Dependencies: 221
-- Data for Name: cantidad_gondolas_fg; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cantidad_gondolas_fg (id, id_gondola, cantidad_gon, id_empresa, id_planta, id_tipo_carga, total_carga, fecha, comentario) FROM stdin;
1	1	3	1	1	1	267	2025-02-28	Todo en orden
2	1	6	5	2	2	534	2025-03-02	Probando
4	1	13	3	1	1	1157	2025-03-02	Todo en orden
3	1	9	2	2	1	801	2025-03-04	Probando
5	1	7	3	1	1	623	2025-03-02	Probando
\.


--
-- TOC entry 5065 (class 0 OID 17126)
-- Dependencies: 223
-- Data for Name: cantidad_gondolas_teu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cantidad_gondolas_teu (id, id_gondola, cantidad_gon, id_empresa, id_mina, id_tipo_carga, total_carga, fecha, comentario) FROM stdin;
1	1	5	1	1	1	445	2025-02-28	Todo en orden
2	1	10	5	2	1	890	2025-03-01	Probando
4	1	13	2	3	1	1157	2025-03-03	Bien
3	1	18	1	1	1	1602	2025-03-04	Probando
\.


--
-- TOC entry 5067 (class 0 OID 17132)
-- Dependencies: 225
-- Data for Name: cantidad_tolvas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cantidad_tolvas (id, id_tolva, cantidad, id_planta, id_tipo_carga, total_carga, fecha, comentario, id_empresa) FROM stdin;
1	2	7	2	2	630	2025-02-28	Todo mal	1
2	2	17	2	1	1530	2025-03-05	Probando	5
3	2	7	1	1	630	2025-03-03	Probando	5
\.


--
-- TOC entry 5069 (class 0 OID 17138)
-- Dependencies: 227
-- Data for Name: carga_barco_pzo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carga_barco_pzo (id, id_mineral, cantidad, nombre_barco, num_embarque, destino, id_empresa, exportacion, fecha, comentario) FROM stdin;
2	1	8000	Barco1	1268	Wuhan	1	f	2025-03-02	Probando
3	2	15000	Barco2	1750	Wuhan	1	t	2025-03-06	Bien
\.


--
-- TOC entry 5071 (class 0 OID 17144)
-- Dependencies: 229
-- Data for Name: carga_de_vagones_cd_piar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carga_de_vagones_cd_piar (id, id_cantidad_gon_teu, id_cantidad_gon_fg, id_cantidad_tol) FROM stdin;
1	1	1	1
2	2	\N	\N
3	\N	3	\N
4	\N	4	\N
5	\N	\N	2
6	3	\N	\N
7	4	\N	\N
8	\N	5	\N
9	\N	\N	3
\.


--
-- TOC entry 5073 (class 0 OID 17148)
-- Dependencies: 231
-- Data for Name: despacho_nacional; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.despacho_nacional (id, id_tipo_mineral, id_empresa_d, cant_vagones, total_toneladas, fecha, comentario) FROM stdin;
1	1	3	18	1620	2025-03-02	Todo en orden
2	2	5	20	1800	2025-03-04	Bien
\.


--
-- TOC entry 5075 (class 0 OID 17154)
-- Dependencies: 233
-- Data for Name: empresa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.empresa (id, nombre) FROM stdin;
1	Ferrominera
2	Venalum
3	Alcasa
5	Visco
6	Sidor
\.


--
-- TOC entry 5076 (class 0 OID 17159)
-- Dependencies: 234
-- Data for Name: empresa_extranjera; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.empresa_extranjera (id, nombre) FROM stdin;
1	China
2	India
\.


--
-- TOC entry 5079 (class 0 OID 17166)
-- Dependencies: 237
-- Data for Name: excavacion_cd_piar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.excavacion_cd_piar (id, id_tipo_material, cantidad, id_mina, id_empresa, fecha, comentario) FROM stdin;
1	1	15000	1	1	2025-02-20	Todo en orden
3	\N	5000	\N	\N	2025-02-22	Todo en orden
4	\N	1111	\N	\N	2025-02-21	Todo en orden
5	\N	1111	\N	\N	2025-02-21	Todo en orden
6	\N	1111	\N	\N	2025-02-25	Todo en orden
7	\N	11111	\N	\N	2025-02-25	Todo en orden
2	2	10000	1	3	2025-02-21	Todo en orden
12	\N	15000	\N	\N	2025-02-27	Todo en orden
13	1	15000	2	2	2025-02-27	Todo en orden
16	1	1350	1	1	2025-03-13	Todo en orden
17	1	10000	1	6	2025-03-13	Todo en orden
\.


--
-- TOC entry 5081 (class 0 OID 17172)
-- Dependencies: 239
-- Data for Name: inventario_cd_piar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inventario_cd_piar (id, id_m_excavado, id_m_procesado) FROM stdin;
1	2	\N
2	\N	1
3	\N	2
4	\N	3
5	3	\N
6	\N	4
7	4	\N
\.


--
-- TOC entry 5083 (class 0 OID 17176)
-- Dependencies: 241
-- Data for Name: inventario_exc_cd_piar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inventario_exc_cd_piar (id, id_tipo, id_mina, cantidad, fecha, comentario) FROM stdin;
1	1	1	13500	2025-03-01	Todo ordenado
2	1	1	10000	2025-03-02	Probando
3	2	3	8000	2025-03-03	Perfecto
4	2	2	370	2025-03-05	En orden
\.


--
-- TOC entry 5085 (class 0 OID 17182)
-- Dependencies: 243
-- Data for Name: inventario_minerales_pzo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inventario_minerales_pzo (id, id_tipo_mineral, cantidad, id_ubicacion, fecha, comentario) FROM stdin;
1	1	500	1	2025-03-01	Probando
\.


--
-- TOC entry 5087 (class 0 OID 17188)
-- Dependencies: 245
-- Data for Name: inventario_proc_cd_piar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inventario_proc_cd_piar (id, id_planta, id_tipo, cantidad, fecha, comentario) FROM stdin;
1	1	1	12000	2025-03-02	Todo en orden
2	1	2	10000	2025-03-01	Probando
3	2	2	10000	2025-03-01	Probando
4	2	2	1500	2025-03-03	Todo en orden
\.


--
-- TOC entry 5089 (class 0 OID 17194)
-- Dependencies: 247
-- Data for Name: inventarios_preproducidos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.inventarios_preproducidos (id, id_producto, cantidad_prod, id_subproducto, cantidad_sub, fecha, comentario) FROM stdin;
1	1	10200	2	5000	2025-03-02	En orden
\.


--
-- TOC entry 5091 (class 0 OID 17200)
-- Dependencies: 249
-- Data for Name: material_excavado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.material_excavado (id, tipo) FROM stdin;
1	TEU
2	MPNC
\.


--
-- TOC entry 5093 (class 0 OID 17206)
-- Dependencies: 251
-- Data for Name: minas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.minas (id, nombre) FROM stdin;
1	Los Barrancos
2	San Joaquin
3	Mina de ejemplo
\.


--
-- TOC entry 5095 (class 0 OID 17212)
-- Dependencies: 253
-- Data for Name: mineral_procesado; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mineral_procesado (id, tipo) FROM stdin;
1	Mineral Fino
2	Mineral Grueso
\.


--
-- TOC entry 5097 (class 0 OID 17218)
-- Dependencies: 255
-- Data for Name: operaciones_siderurgicas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.operaciones_siderurgicas (id, cantidad_prod, fecha, comentario, id_planta_s) FROM stdin;
1	12000	2025-03-02	Todo en orden	1
2	8500	2025-03-03	En orden	2
\.


--
-- TOC entry 5099 (class 0 OID 17224)
-- Dependencies: 257
-- Data for Name: planta; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.planta (id, nombre) FROM stdin;
1	Las Cribas
2	La Trituradora
\.


--
-- TOC entry 5101 (class 0 OID 17230)
-- Dependencies: 259
-- Data for Name: plantas_siderurgicas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.plantas_siderurgicas (id, nombre) FROM stdin;
2	Planta2
1	Planta1
\.


--
-- TOC entry 5103 (class 0 OID 17236)
-- Dependencies: 261
-- Data for Name: procesamiento_pt_cd_piar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.procesamiento_pt_cd_piar (id, id_planta, id_empresa, id_mineral_proc, cantidad, fecha, comentario) FROM stdin;
1	1	1	1	10000	2025-02-27	Perfecto Estado
2	2	5	2	15000	2025-02-28	Todo en orden
3	1	6	1	8500	2025-03-04	Fino
4	2	5	2	12000	2025-03-05	En orden
\.


--
-- TOC entry 5105 (class 0 OID 17242)
-- Dependencies: 263
-- Data for Name: produccion_total_pzo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.produccion_total_pzo (id, cant_volteo, cant_vaciado, cant_fino, cant_grueso, total_producido, fecha, comentario) FROM stdin;
1	10000	10000	15000	5000	19999	2025-03-02	Probando
2	15300	12000	15300	12000	27300	2025-03-04	Todo en orden
3	1200	1500	1200	1500	2700	2025-03-05	Todo en orden
\.


--
-- TOC entry 5107 (class 0 OID 17248)
-- Dependencies: 265
-- Data for Name: productos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.productos (id, nombre) FROM stdin;
1	Producto1
2	Producto2
\.


--
-- TOC entry 5109 (class 0 OID 17254)
-- Dependencies: 267
-- Data for Name: subproductos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.subproductos (id, nombre) FROM stdin;
1	Subproducto1
2	Subproducto2
\.


--
-- TOC entry 5111 (class 0 OID 17260)
-- Dependencies: 269
-- Data for Name: tipos_de_minerales_pzo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipos_de_minerales_pzo (id, nombre) FROM stdin;
1	FSF-1
2	FSF-2
\.


--
-- TOC entry 5113 (class 0 OID 17266)
-- Dependencies: 271
-- Data for Name: transporte_linea_principal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.transporte_linea_principal (id, cant_vagones, cant_gondolas, cant_tolvas, cant_trenes_p, cant_trenes_e, cant_trenes_a, fecha, comentario, total) FROM stdin;
2	8	5	3	2	0	0	2025-03-02	Todo en orden	715
1	7	3	4	3	0	0	2025-03-02	Probando	766
3	22	16	6	3	0	0	2025-03-04	Bien	1964
\.


--
-- TOC entry 5115 (class 0 OID 17272)
-- Dependencies: 273
-- Data for Name: vagones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.vagones (id, tipo, capacidad) FROM stdin;
1	Gondola	89
2	Tolva	90
\.


--
-- TOC entry 5118 (class 0 OID 25245)
-- Dependencies: 276
-- Data for Name: zf_usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.zf_usuarios (id, nombre) FROM stdin;
\.


--
-- TOC entry 5155 (class 0 OID 0)
-- Dependencies: 218
-- Name: almacenes_minerales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.almacenes_minerales_id_seq', 2, true);


--
-- TOC entry 5156 (class 0 OID 0)
-- Dependencies: 220
-- Name: camiones_pzo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.camiones_pzo_id_seq', 2, true);


--
-- TOC entry 5157 (class 0 OID 0)
-- Dependencies: 222
-- Name: cantidad_gondolas_fg_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cantidad_gondolas_fg_id_seq', 5, true);


--
-- TOC entry 5158 (class 0 OID 0)
-- Dependencies: 224
-- Name: cantidad_gondolas_teu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cantidad_gondolas_teu_id_seq', 4, true);


--
-- TOC entry 5159 (class 0 OID 0)
-- Dependencies: 226
-- Name: cantidad_tolvas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cantidad_tolvas_id_seq', 3, true);


--
-- TOC entry 5160 (class 0 OID 0)
-- Dependencies: 228
-- Name: carga_barco_pzo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carga_barco_pzo_id_seq', 3, true);


--
-- TOC entry 5161 (class 0 OID 0)
-- Dependencies: 230
-- Name: carga_de_vagones_cd_piar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carga_de_vagones_cd_piar_id_seq', 9, true);


--
-- TOC entry 5162 (class 0 OID 0)
-- Dependencies: 232
-- Name: despacho_nacional_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.despacho_nacional_id_seq', 2, true);


--
-- TOC entry 5163 (class 0 OID 0)
-- Dependencies: 235
-- Name: empresa_extranjera_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.empresa_extranjera_id_seq', 2, true);


--
-- TOC entry 5164 (class 0 OID 0)
-- Dependencies: 236
-- Name: empresa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.empresa_id_seq', 6, true);


--
-- TOC entry 5165 (class 0 OID 0)
-- Dependencies: 238
-- Name: excavacion_cd_piar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.excavacion_cd_piar_id_seq', 15, true);


--
-- TOC entry 5166 (class 0 OID 0)
-- Dependencies: 240
-- Name: inventario_cd_piar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.inventario_cd_piar_id_seq', 7, true);


--
-- TOC entry 5167 (class 0 OID 0)
-- Dependencies: 242
-- Name: inventario_exc_cd_piar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.inventario_exc_cd_piar_id_seq', 4, true);


--
-- TOC entry 5168 (class 0 OID 0)
-- Dependencies: 244
-- Name: inventario_minerales_pzo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.inventario_minerales_pzo_id_seq', 1, true);


--
-- TOC entry 5169 (class 0 OID 0)
-- Dependencies: 246
-- Name: inventario_proc_cd_piar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.inventario_proc_cd_piar_id_seq', 4, true);


--
-- TOC entry 5170 (class 0 OID 0)
-- Dependencies: 248
-- Name: inventarios_preproducidos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.inventarios_preproducidos_id_seq', 1, true);


--
-- TOC entry 5171 (class 0 OID 0)
-- Dependencies: 250
-- Name: material_excavado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.material_excavado_id_seq', 4, true);


--
-- TOC entry 5172 (class 0 OID 0)
-- Dependencies: 252
-- Name: minas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.minas_id_seq', 3, true);


--
-- TOC entry 5173 (class 0 OID 0)
-- Dependencies: 254
-- Name: mineral_procesado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mineral_procesado_id_seq', 2, true);


--
-- TOC entry 5174 (class 0 OID 0)
-- Dependencies: 256
-- Name: operaciones_siderurgicas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.operaciones_siderurgicas_id_seq', 2, true);


--
-- TOC entry 5175 (class 0 OID 0)
-- Dependencies: 258
-- Name: planta_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.planta_id_seq', 2, true);


--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 260
-- Name: plantas_siderurgicas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.plantas_siderurgicas_id_seq', 2, true);


--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 262
-- Name: procesamiento_pt_cd_piar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.procesamiento_pt_cd_piar_id_seq', 4, true);


--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 264
-- Name: produccion_total_pzo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.produccion_total_pzo_id_seq', 3, true);


--
-- TOC entry 5179 (class 0 OID 0)
-- Dependencies: 266
-- Name: productos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.productos_id_seq', 2, true);


--
-- TOC entry 5180 (class 0 OID 0)
-- Dependencies: 268
-- Name: subproductos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.subproductos_id_seq', 2, true);


--
-- TOC entry 5181 (class 0 OID 0)
-- Dependencies: 270
-- Name: tipos_de_minerales_pzo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipos_de_minerales_pzo_id_seq', 2, true);


--
-- TOC entry 5182 (class 0 OID 0)
-- Dependencies: 272
-- Name: transporte_linea_principal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.transporte_linea_principal_id_seq', 3, true);


--
-- TOC entry 5183 (class 0 OID 0)
-- Dependencies: 274
-- Name: vagones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.vagones_id_seq', 2, true);


--
-- TOC entry 5184 (class 0 OID 0)
-- Dependencies: 275
-- Name: zf_usuarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.zf_usuarios_id_seq', 1, false);


--
-- TOC entry 4817 (class 2606 OID 17308)
-- Name: almacenes_minerales almacenes_minerales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.almacenes_minerales
    ADD CONSTRAINT almacenes_minerales_pkey PRIMARY KEY (id);


--
-- TOC entry 4819 (class 2606 OID 17310)
-- Name: camiones_pzo camiones_pzo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.camiones_pzo
    ADD CONSTRAINT camiones_pzo_pkey PRIMARY KEY (id);


--
-- TOC entry 4821 (class 2606 OID 17312)
-- Name: cantidad_gondolas_fg cantidad_gondolas_fg_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_fg
    ADD CONSTRAINT cantidad_gondolas_fg_pkey PRIMARY KEY (id);


--
-- TOC entry 4823 (class 2606 OID 17314)
-- Name: cantidad_gondolas_teu cantidad_gondolas_teu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_teu
    ADD CONSTRAINT cantidad_gondolas_teu_pkey PRIMARY KEY (id);


--
-- TOC entry 4825 (class 2606 OID 17316)
-- Name: cantidad_tolvas cantidad_tolvas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_tolvas
    ADD CONSTRAINT cantidad_tolvas_pkey PRIMARY KEY (id);


--
-- TOC entry 4827 (class 2606 OID 17318)
-- Name: carga_barco_pzo carga_barco_pzo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_barco_pzo
    ADD CONSTRAINT carga_barco_pzo_pkey PRIMARY KEY (id);


--
-- TOC entry 4829 (class 2606 OID 17320)
-- Name: carga_de_vagones_cd_piar carga_de_vagones_cd_piar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_de_vagones_cd_piar
    ADD CONSTRAINT carga_de_vagones_cd_piar_pkey PRIMARY KEY (id);


--
-- TOC entry 4831 (class 2606 OID 17322)
-- Name: despacho_nacional despacho_nacional_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.despacho_nacional
    ADD CONSTRAINT despacho_nacional_pkey PRIMARY KEY (id);


--
-- TOC entry 4835 (class 2606 OID 17324)
-- Name: empresa_extranjera empresa_extranjera_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresa_extranjera
    ADD CONSTRAINT empresa_extranjera_pkey PRIMARY KEY (id);


--
-- TOC entry 4833 (class 2606 OID 17326)
-- Name: empresa empresa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresa
    ADD CONSTRAINT empresa_pkey PRIMARY KEY (id);


--
-- TOC entry 4837 (class 2606 OID 17328)
-- Name: excavacion_cd_piar excavacion_cd_piar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.excavacion_cd_piar
    ADD CONSTRAINT excavacion_cd_piar_pkey PRIMARY KEY (id);


--
-- TOC entry 4839 (class 2606 OID 17330)
-- Name: inventario_cd_piar inventario_cd_piar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_cd_piar
    ADD CONSTRAINT inventario_cd_piar_pkey PRIMARY KEY (id);


--
-- TOC entry 4841 (class 2606 OID 17332)
-- Name: inventario_exc_cd_piar inventario_exc_cd_piar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_exc_cd_piar
    ADD CONSTRAINT inventario_exc_cd_piar_pkey PRIMARY KEY (id);


--
-- TOC entry 4843 (class 2606 OID 17334)
-- Name: inventario_minerales_pzo inventario_minerales_pzo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_minerales_pzo
    ADD CONSTRAINT inventario_minerales_pzo_pkey PRIMARY KEY (id);


--
-- TOC entry 4845 (class 2606 OID 17336)
-- Name: inventario_proc_cd_piar inventario_proc_cd_piar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_proc_cd_piar
    ADD CONSTRAINT inventario_proc_cd_piar_pkey PRIMARY KEY (id);


--
-- TOC entry 4847 (class 2606 OID 17338)
-- Name: inventarios_preproducidos inventarios_preproducidos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventarios_preproducidos
    ADD CONSTRAINT inventarios_preproducidos_pkey PRIMARY KEY (id);


--
-- TOC entry 4849 (class 2606 OID 17340)
-- Name: material_excavado material_excavado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.material_excavado
    ADD CONSTRAINT material_excavado_pkey PRIMARY KEY (id);


--
-- TOC entry 4851 (class 2606 OID 17342)
-- Name: minas minas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.minas
    ADD CONSTRAINT minas_pkey PRIMARY KEY (id);


--
-- TOC entry 4853 (class 2606 OID 17344)
-- Name: mineral_procesado mineral_procesado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mineral_procesado
    ADD CONSTRAINT mineral_procesado_pkey PRIMARY KEY (id);


--
-- TOC entry 4855 (class 2606 OID 17346)
-- Name: operaciones_siderurgicas operaciones_siderurgicas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.operaciones_siderurgicas
    ADD CONSTRAINT operaciones_siderurgicas_pkey PRIMARY KEY (id);


--
-- TOC entry 4857 (class 2606 OID 17348)
-- Name: planta planta_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planta
    ADD CONSTRAINT planta_pkey PRIMARY KEY (id);


--
-- TOC entry 4859 (class 2606 OID 17350)
-- Name: plantas_siderurgicas plantas_siderurgicas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.plantas_siderurgicas
    ADD CONSTRAINT plantas_siderurgicas_pkey PRIMARY KEY (id);


--
-- TOC entry 4861 (class 2606 OID 17352)
-- Name: procesamiento_pt_cd_piar procesamiento_pt_cd_piar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.procesamiento_pt_cd_piar
    ADD CONSTRAINT procesamiento_pt_cd_piar_pkey PRIMARY KEY (id);


--
-- TOC entry 4863 (class 2606 OID 17354)
-- Name: produccion_total_pzo produccion_total_pzo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.produccion_total_pzo
    ADD CONSTRAINT produccion_total_pzo_pkey PRIMARY KEY (id);


--
-- TOC entry 4865 (class 2606 OID 17356)
-- Name: productos productos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.productos
    ADD CONSTRAINT productos_pkey PRIMARY KEY (id);


--
-- TOC entry 4867 (class 2606 OID 17358)
-- Name: subproductos subproductos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.subproductos
    ADD CONSTRAINT subproductos_pkey PRIMARY KEY (id);


--
-- TOC entry 4869 (class 2606 OID 17360)
-- Name: tipos_de_minerales_pzo tipos_de_minerales_pzo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipos_de_minerales_pzo
    ADD CONSTRAINT tipos_de_minerales_pzo_pkey PRIMARY KEY (id);


--
-- TOC entry 4871 (class 2606 OID 17362)
-- Name: transporte_linea_principal transporte_linea_principal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.transporte_linea_principal
    ADD CONSTRAINT transporte_linea_principal_pkey PRIMARY KEY (id);


--
-- TOC entry 4873 (class 2606 OID 17364)
-- Name: vagones vagones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.vagones
    ADD CONSTRAINT vagones_pkey PRIMARY KEY (id);


--
-- TOC entry 4875 (class 2606 OID 25252)
-- Name: zf_usuarios zf_usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.zf_usuarios
    ADD CONSTRAINT zf_usuarios_pkey PRIMARY KEY (id);


--
-- TOC entry 4876 (class 2606 OID 17365)
-- Name: camiones_pzo camiones_pzo_id_empresa_d_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.camiones_pzo
    ADD CONSTRAINT camiones_pzo_id_empresa_d_fkey FOREIGN KEY (id_empresa_d) REFERENCES public.empresa(id) NOT VALID;


--
-- TOC entry 4877 (class 2606 OID 17370)
-- Name: camiones_pzo camiones_pzo_id_tipo_mineral_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.camiones_pzo
    ADD CONSTRAINT camiones_pzo_id_tipo_mineral_fkey FOREIGN KEY (id_tipo_mineral) REFERENCES public.mineral_procesado(id) NOT VALID;


--
-- TOC entry 4878 (class 2606 OID 17375)
-- Name: cantidad_gondolas_fg cantidad_gondolas_fg_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_fg
    ADD CONSTRAINT cantidad_gondolas_fg_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES public.empresa(id) ON DELETE CASCADE;


--
-- TOC entry 4879 (class 2606 OID 17380)
-- Name: cantidad_gondolas_fg cantidad_gondolas_fg_id_gondola_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_fg
    ADD CONSTRAINT cantidad_gondolas_fg_id_gondola_fkey FOREIGN KEY (id_gondola) REFERENCES public.vagones(id) ON DELETE CASCADE;


--
-- TOC entry 4880 (class 2606 OID 17385)
-- Name: cantidad_gondolas_fg cantidad_gondolas_fg_id_planta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_fg
    ADD CONSTRAINT cantidad_gondolas_fg_id_planta_fkey FOREIGN KEY (id_planta) REFERENCES public.planta(id) ON DELETE CASCADE;


--
-- TOC entry 4881 (class 2606 OID 17390)
-- Name: cantidad_gondolas_fg cantidad_gondolas_fg_id_tipo_carga_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_fg
    ADD CONSTRAINT cantidad_gondolas_fg_id_tipo_carga_fkey FOREIGN KEY (id_tipo_carga) REFERENCES public.mineral_procesado(id) ON DELETE CASCADE;


--
-- TOC entry 4882 (class 2606 OID 17395)
-- Name: cantidad_gondolas_teu cantidad_gondolas_teu_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_teu
    ADD CONSTRAINT cantidad_gondolas_teu_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES public.empresa(id) ON DELETE CASCADE;


--
-- TOC entry 4883 (class 2606 OID 17400)
-- Name: cantidad_gondolas_teu cantidad_gondolas_teu_id_gondola_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_teu
    ADD CONSTRAINT cantidad_gondolas_teu_id_gondola_fkey FOREIGN KEY (id_gondola) REFERENCES public.vagones(id) ON DELETE CASCADE;


--
-- TOC entry 4884 (class 2606 OID 17405)
-- Name: cantidad_gondolas_teu cantidad_gondolas_teu_id_mina_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_teu
    ADD CONSTRAINT cantidad_gondolas_teu_id_mina_fkey FOREIGN KEY (id_mina) REFERENCES public.minas(id) ON DELETE CASCADE;


--
-- TOC entry 4885 (class 2606 OID 17410)
-- Name: cantidad_gondolas_teu cantidad_gondolas_teu_id_tipo_carga_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_gondolas_teu
    ADD CONSTRAINT cantidad_gondolas_teu_id_tipo_carga_fkey FOREIGN KEY (id_tipo_carga) REFERENCES public.material_excavado(id) ON DELETE CASCADE;


--
-- TOC entry 4886 (class 2606 OID 17415)
-- Name: cantidad_tolvas cantidad_tolvas_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_tolvas
    ADD CONSTRAINT cantidad_tolvas_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES public.empresa(id) NOT VALID;


--
-- TOC entry 4887 (class 2606 OID 17420)
-- Name: cantidad_tolvas cantidad_tolvas_id_planta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_tolvas
    ADD CONSTRAINT cantidad_tolvas_id_planta_fkey FOREIGN KEY (id_planta) REFERENCES public.planta(id);


--
-- TOC entry 4888 (class 2606 OID 17425)
-- Name: cantidad_tolvas cantidad_tolvas_id_tipo_carga_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_tolvas
    ADD CONSTRAINT cantidad_tolvas_id_tipo_carga_fkey FOREIGN KEY (id_tipo_carga) REFERENCES public.mineral_procesado(id);


--
-- TOC entry 4889 (class 2606 OID 17430)
-- Name: cantidad_tolvas cantidad_tolvas_id_tolva_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cantidad_tolvas
    ADD CONSTRAINT cantidad_tolvas_id_tolva_fkey FOREIGN KEY (id_tolva) REFERENCES public.vagones(id);


--
-- TOC entry 4890 (class 2606 OID 17435)
-- Name: carga_barco_pzo carga_barco_pzo_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_barco_pzo
    ADD CONSTRAINT carga_barco_pzo_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES public.empresa_extranjera(id);


--
-- TOC entry 4891 (class 2606 OID 17440)
-- Name: carga_barco_pzo carga_barco_pzo_id_mineral_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_barco_pzo
    ADD CONSTRAINT carga_barco_pzo_id_mineral_fkey FOREIGN KEY (id_mineral) REFERENCES public.tipos_de_minerales_pzo(id);


--
-- TOC entry 4892 (class 2606 OID 17445)
-- Name: carga_de_vagones_cd_piar carga_de_vagones_cd_piar_id_cantidad_gon_fg_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_de_vagones_cd_piar
    ADD CONSTRAINT carga_de_vagones_cd_piar_id_cantidad_gon_fg_fkey FOREIGN KEY (id_cantidad_gon_fg) REFERENCES public.cantidad_gondolas_fg(id) ON DELETE CASCADE;


--
-- TOC entry 4893 (class 2606 OID 17450)
-- Name: carga_de_vagones_cd_piar carga_de_vagones_cd_piar_id_cantidad_gon_teu_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_de_vagones_cd_piar
    ADD CONSTRAINT carga_de_vagones_cd_piar_id_cantidad_gon_teu_fkey FOREIGN KEY (id_cantidad_gon_teu) REFERENCES public.cantidad_gondolas_teu(id) ON DELETE CASCADE;


--
-- TOC entry 4894 (class 2606 OID 17455)
-- Name: carga_de_vagones_cd_piar carga_de_vagones_cd_piar_id_cantidad_tol_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carga_de_vagones_cd_piar
    ADD CONSTRAINT carga_de_vagones_cd_piar_id_cantidad_tol_fkey FOREIGN KEY (id_cantidad_tol) REFERENCES public.cantidad_tolvas(id) ON DELETE CASCADE;


--
-- TOC entry 4895 (class 2606 OID 17460)
-- Name: despacho_nacional despacho_nacional_id_empresa_d_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.despacho_nacional
    ADD CONSTRAINT despacho_nacional_id_empresa_d_fkey FOREIGN KEY (id_empresa_d) REFERENCES public.empresa(id);


--
-- TOC entry 4896 (class 2606 OID 17465)
-- Name: despacho_nacional despacho_nacional_id_tipo_mineral_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.despacho_nacional
    ADD CONSTRAINT despacho_nacional_id_tipo_mineral_fkey FOREIGN KEY (id_tipo_mineral) REFERENCES public.mineral_procesado(id);


--
-- TOC entry 4897 (class 2606 OID 17470)
-- Name: excavacion_cd_piar excavacion_cd_piar_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.excavacion_cd_piar
    ADD CONSTRAINT excavacion_cd_piar_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES public.empresa(id);


--
-- TOC entry 4898 (class 2606 OID 17475)
-- Name: excavacion_cd_piar excavacion_cd_piar_id_mina_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.excavacion_cd_piar
    ADD CONSTRAINT excavacion_cd_piar_id_mina_fkey FOREIGN KEY (id_mina) REFERENCES public.minas(id);


--
-- TOC entry 4899 (class 2606 OID 17480)
-- Name: excavacion_cd_piar excavacion_cd_piar_id_tipo_material_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.excavacion_cd_piar
    ADD CONSTRAINT excavacion_cd_piar_id_tipo_material_fkey FOREIGN KEY (id_tipo_material) REFERENCES public.material_excavado(id);


--
-- TOC entry 4900 (class 2606 OID 17485)
-- Name: inventario_cd_piar inventario_cd_piar_id_m_excavado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_cd_piar
    ADD CONSTRAINT inventario_cd_piar_id_m_excavado_fkey FOREIGN KEY (id_m_excavado) REFERENCES public.inventario_exc_cd_piar(id);


--
-- TOC entry 4901 (class 2606 OID 17490)
-- Name: inventario_cd_piar inventario_cd_piar_id_m_procesado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_cd_piar
    ADD CONSTRAINT inventario_cd_piar_id_m_procesado_fkey FOREIGN KEY (id_m_procesado) REFERENCES public.inventario_proc_cd_piar(id);


--
-- TOC entry 4902 (class 2606 OID 17495)
-- Name: inventario_exc_cd_piar inventario_exc_cd_piar_id_mina_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_exc_cd_piar
    ADD CONSTRAINT inventario_exc_cd_piar_id_mina_fkey FOREIGN KEY (id_mina) REFERENCES public.minas(id);


--
-- TOC entry 4903 (class 2606 OID 17500)
-- Name: inventario_exc_cd_piar inventario_exc_cd_piar_id_tipo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_exc_cd_piar
    ADD CONSTRAINT inventario_exc_cd_piar_id_tipo_fkey FOREIGN KEY (id_tipo) REFERENCES public.material_excavado(id);


--
-- TOC entry 4904 (class 2606 OID 17505)
-- Name: inventario_minerales_pzo inventario_minerales_pzo_id_tipo_mineral_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_minerales_pzo
    ADD CONSTRAINT inventario_minerales_pzo_id_tipo_mineral_fkey FOREIGN KEY (id_tipo_mineral) REFERENCES public.tipos_de_minerales_pzo(id) ON DELETE CASCADE;


--
-- TOC entry 4905 (class 2606 OID 17510)
-- Name: inventario_minerales_pzo inventario_minerales_pzo_id_ubicacion_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_minerales_pzo
    ADD CONSTRAINT inventario_minerales_pzo_id_ubicacion_fkey FOREIGN KEY (id_ubicacion) REFERENCES public.almacenes_minerales(id) ON DELETE CASCADE;


--
-- TOC entry 4906 (class 2606 OID 17515)
-- Name: inventario_proc_cd_piar inventario_proc_cd_piar_id_planta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_proc_cd_piar
    ADD CONSTRAINT inventario_proc_cd_piar_id_planta_fkey FOREIGN KEY (id_planta) REFERENCES public.planta(id);


--
-- TOC entry 4907 (class 2606 OID 17520)
-- Name: inventario_proc_cd_piar inventario_proc_cd_piar_id_tipo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventario_proc_cd_piar
    ADD CONSTRAINT inventario_proc_cd_piar_id_tipo_fkey FOREIGN KEY (id_tipo) REFERENCES public.mineral_procesado(id);


--
-- TOC entry 4908 (class 2606 OID 17525)
-- Name: inventarios_preproducidos inventarios_preproducidos_id_producto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventarios_preproducidos
    ADD CONSTRAINT inventarios_preproducidos_id_producto_fkey FOREIGN KEY (id_producto) REFERENCES public.productos(id) ON DELETE CASCADE;


--
-- TOC entry 4909 (class 2606 OID 17530)
-- Name: inventarios_preproducidos inventarios_preproducidos_id_subproducto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.inventarios_preproducidos
    ADD CONSTRAINT inventarios_preproducidos_id_subproducto_fkey FOREIGN KEY (id_subproducto) REFERENCES public.subproductos(id) ON DELETE CASCADE;


--
-- TOC entry 4910 (class 2606 OID 17535)
-- Name: operaciones_siderurgicas operaciones_siderurgicas_id_planta_s_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.operaciones_siderurgicas
    ADD CONSTRAINT operaciones_siderurgicas_id_planta_s_fkey FOREIGN KEY (id_planta_s) REFERENCES public.plantas_siderurgicas(id) NOT VALID;


--
-- TOC entry 4911 (class 2606 OID 17540)
-- Name: procesamiento_pt_cd_piar procesamiento_pt_cd_piar_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.procesamiento_pt_cd_piar
    ADD CONSTRAINT procesamiento_pt_cd_piar_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES public.empresa(id);


--
-- TOC entry 4912 (class 2606 OID 17545)
-- Name: procesamiento_pt_cd_piar procesamiento_pt_cd_piar_id_mineral_proc_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.procesamiento_pt_cd_piar
    ADD CONSTRAINT procesamiento_pt_cd_piar_id_mineral_proc_fkey FOREIGN KEY (id_mineral_proc) REFERENCES public.mineral_procesado(id);


--
-- TOC entry 4913 (class 2606 OID 17550)
-- Name: procesamiento_pt_cd_piar procesamiento_pt_cd_piar_id_planta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.procesamiento_pt_cd_piar
    ADD CONSTRAINT procesamiento_pt_cd_piar_id_planta_fkey FOREIGN KEY (id_planta) REFERENCES public.planta(id);


-- Completed on 2025-03-14 08:05:34

--
-- PostgreSQL database dump complete
--

