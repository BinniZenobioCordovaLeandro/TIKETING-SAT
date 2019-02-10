-- Ciao Gabriele...
-- è qua un po' di spiegazione del codice dentro del'altra APP.

-- CREDENZIALI DB PROD:
--   DS_HOST 		= 'prdgpia04.gpi.it';
--   DS_PORT 		= '1521';
--   DS_USR 		= 'SATMAN';
--   DS_PWD 		= 'S3NT13R1dimontagn4!';
--   DS_SERVICE 	= 'caleido';


/* CODICE DENtRO DI APLICATION . */
BEGIN p_pln_prepare_data_3(:operator, :mode); END; /* questa procedura carga le tabelle : TMP_ACTIVITIES_INDEX, TMP_ACTIVITY_DAYS, TMP_ARGS

/* PRENDENDO QUESTA QUERY prende tutte le DATE del calendario. */
select cal.calendar_date as calendar_date_original, to_char(cal.calendar_date, 'yyyy-mm-dd') as calendar_date, cal.is_holiday,
count(distinct tt.id_attivita) as num_attivita,
sum(coalesce(td.ore_attivita, 0)) as ore_attivita
from satman.tmp_args a, satman.t_calendar cal
left outer join satman.tmp_activity_days td on  td.data_attivita = cal.calendar_date
left outer join satman.tmp_activities_index tt on  tt.tipo_attivita = td.tipo_attivita and tt.id_attivita = td.id_attivita
where cal.calendar_date between a.data_da and a.data_a
-- and tt.validita = 0
group by cal.calendar_date, cal.is_holiday
order by 1, 2;


/* PRENDENDO QUESTA QUERY prende tutte le attività (FERIE O PERMESSO) deggli Operatori. */
select
tt.operatore,
tt.prodotto,
tt.tipo_attivita,
tt.id_attivita,
to_char(to_number(replace(tt.id_attivita, 'HD', '0'))) as id_attivita_short,
cal.is_holiday,
td.data_attivita as data_pianif,
--coalesce(td.ore_attivita, 0)*100000 as ore_attivita,
(coalesce(td.ore_attivita, 0)*100000)/100000 as ore_attivita,
tt.titolo_attivita,
tt.cliente,
replace(replace(tt.descr_attivita, '<', '&lt;'), '>', '&gt;') as descr_attivita,
to_char(td.data_attivita, 'yyyy-mm-dd') as mapkey,
coalesce(act.note, to_clob('(nessuna nota)')) as note, act.richiedente,
tt.stato, tt.esterna
from satman.t_calendar cal
inner join satman.tmp_activity_days td on cal.calendar_date = td.data_attivita
inner join satman.tmp_activities_index tt on  tt.tipo_attivita = td.tipo_attivita and tt.id_attivita = td.id_attivita
inner join VW_TICKTASK_SAT act on  act.id_attivita = tt.id_attivita
where tt.validita = 0 and tt.operatore = coalesce(:operator, tt.operatore)
-- and td.data_attivita is not null
order by tt.operatore, td.data_attivita, tt.id_attivita;


-- Questa ultima query è per il controllo del CALENDARIO del operatore.
-- per esempio si prendo al operatore di utente 'lfaccenda'

select
tt.operatore,
tt.prodotto,
tt.tipo_attivita,
tt.id_attivita,
to_char(to_number(replace(tt.id_attivita, 'HD', '0'))) as id_attivita_short,
cal.is_holiday,
td.data_attivita as data_pianif,
--coalesce(td.ore_attivita, 0)*100000 as ore_attivita,
(coalesce(td.ore_attivita, 0)*100000)/100000 as ore_attivita,
tt.titolo_attivita,
tt.cliente,
replace(replace(tt.descr_attivita, '<', '&lt;'), '>', '&gt;') as descr_attivita,
to_char(td.data_attivita, 'yyyy-mm-dd') as mapkey,
coalesce(act.note, to_clob('(nessuna nota)')) as note, act.richiedente,
tt.stato, tt.esterna
from satman.t_calendar cal
inner join satman.tmp_activity_days td on cal.calendar_date = td.data_attivita
inner join satman.tmp_activities_index tt on  tt.tipo_attivita = td.tipo_attivita and tt.id_attivita = td.id_attivita
inner join VW_TICKTASK_SAT act on  act.id_attivita = tt.id_attivita
where tt.validita = 0 and tt.operatore = coalesce('lfaccenda', tt.operatore)
-- and td.data_attivita is not null
order by tt.operatore, td.data_attivita, tt.id_attivita;

-- mi arriva questi dati. :

OPERATORE	PRODOTTO	TIPO_ATTIVITA	ID_ATTIVITA	ID_ATTIVITA_SHORT	IS_HOLIDAY	DATA_PIANIF	ORE_ATTIVITA	TITOLO_ATTIVITA	CLIENTE	DESCR_ATTIVITA	MAPKEY	NOTE	RICHIEDENTE	STATO	ESTERNA
lfaccenda	ASSENZE	Task	000000000951922	951922	0	2018-07-27 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-07-27	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000961250	961250	0	2018-08-03 00:00:00	4	PERMESSO	GPI S.P.A.	Permesso	2018-08-03	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000961251	961251	0	2018-08-06 00:00:00	1	PERMESSO	GPI S.P.A.	Permesso	2018-08-06	Dentista	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-13 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-13	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-14 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-14	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-16 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-16	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-17 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-17	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-20 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-20	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-21 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-21	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-22 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-22	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-23 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-23	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000956660	956660	0	2018-08-24 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-08-24	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000861419	861419	0	2018-11-02 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-11-02	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000861420	861420	0	2018-12-24 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-12-24	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000861420	861420	0	2018-12-27 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-12-27	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000861420	861420	0	2018-12-28 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-12-28	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000861420	861420	0	2018-12-31 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2018-12-31	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000861420	861420	0	2019-01-02 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2019-01-02	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000861420	861420	0	2019-01-03 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2019-01-03	(nessuna nota)	[NULL]	Pianificato	0
lfaccenda	ASSENZE	Task	000000000861420	861420	0	2019-01-04 00:00:00	8	FERIE	GPI S.P.A.	Ferie	2019-01-04	(nessuna nota)	[NULL]	Pianificato	0

-- e secondo questi dati posso sapere se è l'operatore, lavorò 8 ore, a messo tempo, o non lavoro, per che sta in ferie...

-- secondo questo se può vedere che dentro del sito:
http://prdtasksat01.gpi.it/satplan/view/presenti.php
/* nelle date 02/08/2018 e 03/08/2018
che nella data 02/08/2018 'lfaccenda', lavorò 8 ore con normalità perche non c'èra nessuna attivita("ferie" oppure "permesso") messa per quella data.
  | 02/08/2018    |
  | lfaccenda (8,0) |

Ma, nell'altra Data 03/08/2018, 'lfaccenda' teneva un permesso per 4 ore... quindi a le "8 ore" che deve lavorare con normalità meno "4 ore" è uguale a un lavorò a "metà di tempo".
  | 03/08/2018    |
  | lfaccenda (4,0) |

l'ultimo caso, nella data 13/08/2018 'lfaccenda' non lavora perche a un permesso per 8 ore, e "8 ore di lavoro normale" meno "8 ore de permesso" è uguale a "non sta lavorando"...
  | 13/08/2018    |
  | l-f-a-c-c-e-n-d-a (0,0) |
*/



-- SI possiamo tennere una tabella:
-- Calendario */

  CREATE TABLE ticketing.t_calendar (
  	CALENDAR_DATE date NOT NULL,
  	WEEK_DAY varchar(20) NOT NULL,
  	WEEK_DAY_NUM numeric NOT NULL,
  	CALENDAR_YEAR numeric NOT NULL,
  	CALENDAR_MONTH numeric NOT NULL,
  	CALENDAR_DAY numeric NOT NULL,
  	IS_HOLIDAY numeric NOT NULL DEFAULT 0,
  	CONSTRAINT "T_CALENDAR_PK" PRIMARY KEY ("CALENDAR_DATE")
  )

-- e un'altra tabella per mostrare la attivita degli Operatori:
CREATE TABLE t_attivita_operatori(
	data_attivita DATE, -- data
	tempo_per_attivita LONG, -- decimal oppure ora
	titolo_attivita VARCHAR2,
	descriptioni_attivita VARCHAR2,
	id_operatore NUMERIC,
	cliente VARCHAR2,
	stato VARCHAR2,
	tipo_attivita VARCHAR2,
	prodotto VARCHAR2
);

-- DOMANDE...
-- O alcuna forma di mettere i dati dentro della tabella che gia esiste (tickets) ? ...

-- ciao.
-- Binni.
