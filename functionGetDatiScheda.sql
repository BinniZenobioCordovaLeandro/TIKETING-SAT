/* ERROR QUANDO LA FUNCTION E DE TIPO CLOB */
oracle.sql.CLOB@61042592
/* QUANDO e varchar2 va normale, pero si y dati sono piu dalla lungeza massima di varchar2, questo se rompe...*/

/* CHIAMATA DALLA SCHEDA  DENTRO DI => HIS*/
function getDatiScheda(encounter, codeScheda){
		try {
			if (encounter && codeScheda) {
				console.log('encounter'+ encounter+', codeScheda'+codeScheda);
				var risposta, fparams = {};
				fparams['functionName'] = 'IEOPERA.GET_DATI_ULTIMA_SCHEDA';
				fparams['v_codeencounter'] = encounter;
				fparams['v_codeform'] = codeScheda;
				var fRes = executeFunction(fparams);
				if (fRes.status === 200) {
					return risposta = fRes.responseText;
				}
			}
		} catch (e) {
			console.log("Warning !, exeception in getDatiScheda().");
		}
	}

getDatiScheda('E2017000032','Anamnesi_esame_obiettivo');

/* chaimata a la function dal database IEOPERA SOLATRIX PROD*/
SELECT IEOPERA.GET_DATI_ULTIMA_SCHEDA('E2017000032','Anamnesi_esame_obiettivo') FROM DUAL;

/* LLa function a privilegi*/
GRANT EXECUTE ON GET_DATI_ULTIMA_SCHEDA TO MDB;

/* START TESTO => FUNCTION DENTRO DI IEOPERA -> SERVER PROD SOLATRIX */
CREATE OR REPLACE FUNCTION IEOPERA.GET_DATI_ULTIMA_SCHEDA(v_codeencounter VARCHAR2, v_codeform VARCHAR2)
RETURN CLOB
AS
	r_table FN_TYPE_TABLE;
	-- Variabili..
	var_idEncounter NUMERIC;
	var_idActiviti NUMERIC;
	var_actype NUMERIC;

	testoJson CLOB;
BEGIN
/* ______ IN ESEGUIMENTO ___________*/
DBMS_OUTPUT.put_line('----**START**-----');

	-- se obtiene il ID del ENCOUNTER !!!
	SELECT ID INTO var_idEncounter FROM HIS.ENC_ENCOUNTERS EE WHERE EE.COUNTER = v_codeencounter;
	DBMS_OUTPUT.put_line('var_idEncounter : '||var_idEncounter);

	-- se obtiene los formularios de cierto form...
	SELECT ACTIVITYID INTO var_idActiviti FROM (SELECT * FROM HIS.FRM_FOLDER_INSTANCES FFI WHERE FFI.REF_ENCOUNTER = var_idEncounter AND FFI.REF_FORM IN (SELECT ID FROM HIS.CAT_FORMS CF WHERE CF.CODE = v_codeform) AND STATUS = 'PUBLISHED' ORDER BY FFI.UPDATE_TIME DESC) WHERE ROWNUM <= 1;
	DBMS_OUTPUT.put_line('var_idActiviti: '||var_idActiviti);

	SELECT REF_ACTYPE INTO var_actype FROM DYNAMICFORMS.ACTIVITIES WHERE ID = var_idActiviti;
	DBMS_OUTPUT.put_line('var_actype: '||var_actype);

	SELECT FN_TYPE_RECORD(dtc.CODE, dti.VALUE, dtc1.CODE, da.ANSWER)
	BULK COLLECT INTO r_table
		FROM DYNAMICFORMS.ANSWERS da
	JOIN DYNAMICFORMS.TMPLITEMS dti
		ON da.REF_TMPLITEM = dti.ID
	JOIN DYNAMICFORMS.TMPLCOMPONENTS dtc
		ON dtc.ID = dti.ID
	JOIN DYNAMICFORMS.TMPLCOMPONENTS dtc1
		ON dtc1.ID = da.REF_TMPLANSWER
	WHERE
		dti.REF_TMPLSECTION IN (SELECT ID FROM DYNAMICFORMS.TMPLSECTIONS dts WHERE dts.REF_ACTYPE = var_actype)
		AND da.REF_ACTIVITY = var_idActiviti AND da.REF_TMPLANSWER IN (SELECT ID FROM DYNAMICFORMS.TMPLANSWERS dta WHERE dta.ID = da.REF_TMPLANSWER AND dta.TYPE_OF_ANSWER NOT IN ('TEXTAREA'));

  /* PROBLEMMA NEL CONTROLLO DE I DATI :*/
	SELECT '{'||IEOPERA.generaJSON(''''||CODE_ANSWER||''':'''||REPLACE(REPLACE(ANSWER,'''','\'''),'
','\n')||'''')||'}' AS json INTO testoJson FROM table(r_table);


	RETURN testoJson;
END;
/* END TESTO => FUNCTION DENTRO DI IEOPERA -> SERVER PROD SOLATRIX */

/* START TESTO => TYPE TYPE_GENERARE_JSON DENTRO DI IEOPERA -> SERVER PROD SOLATRIX */
CREATE TYPE "TYPE_GENERARE_JSON" AS OBJECT
(
	total CLOB,
	STATIC FUNCTION	ODCIAggregateInitialize(sctx IN OUT type_generare_json)
	RETURN NUMBER,

	MEMBER FUNCTION ODCIAggregateIterate(self IN OUT type_generare_json, value IN CLOB)
	RETURN NUMBER,

	MEMBER FUNCTION ODCIAggregateTerminate(SELF IN type_generare_json, returnValue OUT CLOB, flags IN NUMBER)
	RETURN NUMBER,

	MEMBER FUNCTION ODCIAggregateMerge(SELF IN OUT type_generare_json, ctx2 IN type_generare_json)
	RETURN NUMBER
)
CREATE TYPE body type_generare_json
IS
	STATIC FUNCTION ODCIAggregateInitialize(sctx IN OUT type_generare_json)
	RETURN NUMBER
	IS BEGIN
		sctx:=type_generare_json(null); --> inizializa contesto di agregacion !.
		RETURN ODCIConst.Success;
	END;

	MEMBER FUNCTION ODCIAggregateIterate(self IN OUT type_generare_json, value IN CLOB)
	RETURN NUMBER
	IS BEGIN
		self.total := self.total || ', ' || value; --> attualiza el contexto de evaluazion de entrada !
		RETURN ODCIConst.Success;
	END;

	MEMBER FUNCTION ODCIAggregateTerminate(self IN type_generare_json, returnValue OUT CLOB, flags IN number)
	RETURN number
	IS BEGIN
		returnValue := ltrim(self.total,', '); --> finaliza la iteracion, libera memoria y ltrim(), elimina  caracteresa la izquierda.
		RETURN ODCIConst.Success;
	END;

	MEMBER FUNCTION ODCIAggregateMerge(self IN OUT type_generare_json, ctx2 IN type_generare_json)
	RETURN number
	IS
	BEGIN
		self.total := self.total ||  ctx2.total;
		RETURN ODCIConst.Success;
	END;
END;
/* END TESTO => TYPE TYPE_GENERARE_JSON DENTRO DI IEOPERA -> SERVER PROD SOLATRIX */



/* TYPE TABLE */
TYPE FN_TYPE_TABLE IS TABLE OF IEOPERA.FN_TYPE_RECORD

/* TYPE RECORD*/
TYPE FN_TYPE_RECORD AS OBJECT(
	code_question VARCHAR2(400),
	question VARCHAR2(4000),
	code_answer varchar2(400),
	answer VARCHAR2(4000)
)
