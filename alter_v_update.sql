DECLARE @SQLString NVARCHAR(MAX)
DECLARE @BASIC_ID INT
DECLARE @CHAPA_CLOUD_ID VARCHAR(3)
DECLARE @DBNAME NVARCHAR(MAX)
DECLARE @DBExec NVARCHAR(MAX)

SET @SQLString=N''
-- SET @DBNAME=N''

DECLARE cursor_name CURSOR FOR 
	SELECT BASIC_ID,CHAPA_CLOUD_ID FROM baac_cloud_63.dbo.M_BASIC WHERE CHAPA_CLOUD_ID <> '000' ORDER BY CHAPA_CLOUD_ID

	OPEN cursor_name
	FETCH NEXT FROM cursor_name
	INTO @BASIC_ID,@CHAPA_CLOUD_ID

	-- Loop From Cursor
	WHILE (@@FETCH_STATUS = 0) 
	BEGIN 

SET @DBNAME='baac_chapa_'+@CHAPA_CLOUD_ID
SET @DBExec = @DBNAME + N'.sys.sp_executesql';

SET @SQLString = 'ALTER VIEW V_UPDATE_ALERT
AS 
SELECT
	ROW_NUMBER () OVER (ORDER BY member_id ASC) AS member_pk,
	*
FROM
	(
		SELECT
			a.member_id,
			(
				b.prefix_name + a.fname + '' '' + a.lname
			) AS name,
			''แจ้งสมาชิกสมัครใหม่'' AS update_name,
			a.register_date AS date_request,
			list_type = 1,
			a.member_id AS list_id,
			a.tel AS tel,
			(
				CASE
				WHEN (a.create_datetime IS NULL) THEN
					CONVERT (
						VARCHAR,
						a.register_date,
						120
					)
				ELSE
					a.create_datetime
				END
			) AS createtime
		FROM
			M_MEMBER a
		LEFT JOIN PREFIX b ON a.prefix_id = b.prefix_id
		WHERE
			1 = 1
		AND a.member_status = ''0''
		AND a.reg_channel = 1
		UNION ALL
			SELECT
				a.member_id,
				(
					b.prefix_name + b.fname + '' '' + b.lname
				) AS name,
				''แจ้งเปลี่ยนแปลงข้อมูล'' AS update_name,
				a.chg_date AS date_request,
				list_type = 2,
				a.chg_id AS list_id,
				(
					SELECT
						tel
					FROM
						M_MEMBER
					WHERE
						member_id = a.member_id
				) AS tel,
				(
					CASE
					WHEN (a.create_datetime IS NULL) THEN
						CONVERT (VARCHAR, a.chg_date, 120)
					ELSE
						a.create_datetime
					END
				) AS createtime
			FROM
				M_CHG_PROFILE a
			INNER JOIN V_MEMBER b ON a.member_id = b.member_id
			WHERE
				1 = 1
			AND a.approve_status = 0
			AND a.member_id <> 0
			UNION ALL
				SELECT
					b.MEMBER_ID,
					(
						a.ALERT_DEAD_FNAME + '' '' + a.ALERT_DEAD_LNAME
					) AS name,
					''แจ้งเสียชีวิต'' AS update_name,
					a.ALERT_REQ_DATE AS date_request,
					list_type = 3,
					a.ALERT_DEAD_ID AS list_id,
					a.ALERT_DEAD_TEL AS tel,
					(
						CASE
						WHEN (a.create_datetime IS NULL) THEN
							CONVERT (
								VARCHAR,
								a.ALERT_REQ_DATE,
								120
							)
						ELSE
							a.create_datetime
						END
					) AS createtime
				FROM
					M_ALERT_DEAD a
				LEFT JOIN M_MEMBER b ON a.MEMBER_ID = b.member_id
				WHERE
					1 = 1
				AND a.ACTIVE_STATUS = 0
	) AS tb'


		EXEC @DBExec @SQLString 

				FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END
	

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name