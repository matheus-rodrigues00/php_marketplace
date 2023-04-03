--
-- PostgreSQL database dump
--

-- Dumped from database version 15.1
-- Dumped by pg_dump version 15.2 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
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
-- Name: product_types; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.product_types (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    tax_rate numeric(10,2) NOT NULL
);


ALTER TABLE public.product_types OWNER TO postgres;

--
-- Name: product_types_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.product_types_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.product_types_id_seq OWNER TO postgres;

--
-- Name: product_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.product_types_id_seq OWNED BY public.product_types.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.products (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    price numeric(10,2) NOT NULL,
    product_type_id integer NOT NULL
);


ALTER TABLE public.products OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.products_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_id_seq OWNER TO postgres;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.products_id_seq OWNED BY public.products.id;


--
-- Name: sale_items; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sale_items (
    id integer NOT NULL,
    sale_id integer NOT NULL,
    product_id integer NOT NULL,
    quantity integer NOT NULL,
    price numeric(10,2) NOT NULL,
    tax numeric(10,2) NOT NULL
);


ALTER TABLE public.sale_items OWNER TO postgres;

--
-- Name: sale_items_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sale_items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sale_items_id_seq OWNER TO postgres;

--
-- Name: sale_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sale_items_id_seq OWNED BY public.sale_items.id;


--
-- Name: sales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sales (
    id integer NOT NULL,
    user_id integer NOT NULL,
    total numeric(10,2) NOT NULL,
    total_tax numeric(10,2) NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.sales OWNER TO postgres;

--
-- Name: sales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sales_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sales_id_seq OWNER TO postgres;

--
-- Name: sales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sales_id_seq OWNED BY public.sales.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: product_types id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_types ALTER COLUMN id SET DEFAULT nextval('public.product_types_id_seq'::regclass);


--
-- Name: products id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products ALTER COLUMN id SET DEFAULT nextval('public.products_id_seq'::regclass);


--
-- Name: sale_items id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sale_items ALTER COLUMN id SET DEFAULT nextval('public.sale_items_id_seq'::regclass);


--
-- Name: sales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales ALTER COLUMN id SET DEFAULT nextval('public.sales_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: product_types; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.product_types (id, name, tax_rate) FROM stdin;
1	Electronics	29.00
2	Clothing	32.00
3	Home Appliances	75.00
4	Books	20.00
5	Sports Equipment	86.00
6	Beauty and Personal Care	52.00
7	Furniture	99.00
8	Toys and Games	67.00
9	Food and Beverages	42.00
10	Office Supplies	51.00
11	Pet Supplies	87.00
12	Jewelry	44.00
13	Automotive	12.00
14	Musical Instruments	13.00
\.


--
-- Data for Name: products; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.products (id, name, price, product_type_id) FROM stdin;
1	Samsung Galaxy S21	899.99	1
2	Levi's 501 Original Fit Jeans	69.50	2
3	KitchenAid Artisan Stand Mixer	499.99	3
4	The Great Gatsby by F. Scott Fitzgerald	14.99	4
5	Nike Air Zoom Pegasus 38	119.99	5
6	L'Oreal Paris Voluminous Lash Paradise Mascara	9.99	6
7	Ikea Billy Bookcase	79.00	7
8	Lego Star Wars Millennium Falcon	799.99	8
9	Starbucks Pike Place Roast Coffee	12.99	9
10	Sharpie Permanent Markers, Assorted Colors	7.99	10
11	Blue Buffalo Wilderness Chicken Recipe	59.99	11
12	1/2 Carat T.W. Diamond 10K White Gold Stud Earrings	399.99	12
13	Meguiar's Ultimate Liquid Wax	24.99	13
14	Yamaha FG800 Acoustic Guitar	199.99	14
15	Samsung Galaxy S22	899.99	1
16	Nike Air Max 270	149.99	2
17	Breville Smart Oven	249.99	3
18	The Great Gatsby	12.99	4
19	Spalding NBA Basketball	29.99	5
20	LOreal Paris Shampoo	9.99	6
21	Ikea Billy Bookcase	99.99	7
22	Lego Star Wars Set	59.99	8
23	Nescafe Instant Coffee	8.99	9
24	Staples Printer Paper	4.99	10
25	Purina Cat Food	19.99	11
26	Diamond Stud Earrings	1999.99	12
27	Michelin Defender Tires	99.99	13
28	Yamaha Acoustic Guitar	299.99	14
29	Apple iPad	399.99	1
30	Adidas Ultraboost Shoes	179.99	2
31	Vitamix Blender	499.99	3
32	The Catcher in the Rye	10.99	4
33	Wilson Tennis Racket	59.99	5
34	Head & Shoulders Shampoo	7.99	6
35	Ikea Hemnes Dresser	199.99	7
36	Nerf Gun	19.99	8
37	Coca-Cola 12-Pack	5.99	9
38	Sharpie Permanent Markers	8.99	10
39	Purina Dog Food	24.99	11
40	Pearl Necklace	999.99	12
41	Bridgestone Tires	79.99	13
\.


--
-- Data for Name: sale_items; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sale_items (id, sale_id, product_id, quantity, price, tax) FROM stdin;
\.


--
-- Data for Name: sales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sales (id, user_id, total, total_tax, created_at) FROM stdin;
1	1	0.00	0.00	2023-04-03 07:54:44.683682
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, password, created_at) FROM stdin;
1	admin	admin@admin.com	123456	2023-04-03 07:54:44.659877
\.


--
-- Name: product_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.product_types_id_seq', 14, true);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.products_id_seq', 41, true);


--
-- Name: sale_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sale_items_id_seq', 1, false);


--
-- Name: sales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sales_id_seq', 1, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: product_types product_types_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.product_types
    ADD CONSTRAINT product_types_pkey PRIMARY KEY (id);


--
-- Name: products products_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: sale_items sale_items_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sale_items
    ADD CONSTRAINT sale_items_pkey PRIMARY KEY (id);


--
-- Name: sales sales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_pkey PRIMARY KEY (id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: products products_product_type_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.products
    ADD CONSTRAINT products_product_type_id_fkey FOREIGN KEY (product_type_id) REFERENCES public.product_types(id);


--
-- Name: sale_items sale_items_product_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sale_items
    ADD CONSTRAINT sale_items_product_id_fkey FOREIGN KEY (product_id) REFERENCES public.products(id);


--
-- Name: sale_items sale_items_sale_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sale_items
    ADD CONSTRAINT sale_items_sale_id_fkey FOREIGN KEY (sale_id) REFERENCES public.sales(id);


--
-- Name: sales sales_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sales
    ADD CONSTRAINT sales_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- PostgreSQL database dump complete
--

