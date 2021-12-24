ALTER PROCEDURE [dbo].[report_mis_groupby]
AS
BEGIN
DECLARE @SQLString NVARCHAR(MAX)
DECLARE @BASIC_ID INT
DECLARE @CHAPA_CLOUD_ID VARCHAR(3)
DECLARE @DBNAME VARCHAR(100)
DECLARE @maxid VARCHAR(50)
DECLARE @date_run VARCHAR(10)
DECLARE @date_data VARCHAR(10)
DECLARE @s_date VARCHAR(10)
DECLARE @e_date VARCHAR(10)
DECLARE @quater INT
DECLARE @month VARCHAR(100)
DECLARE @S_YEAR INT

SET @SQLString=N''
SET @date_run = convert(VARCHAR(10), getdate(), 23)
SET @date_data = convert(VARCHAR(10), getdate()-1, 23)
SET @month = CONVERT(VARCHAR(10),MONTH(GETDATE()))
SET @S_YEAR =CONVERT(VARCHAR(4),YEAR(GETDATE()))
SET @quater = 1
--SET DEADLOCK_PRIORITY NORMAL 

-- -- เช็คไตรมาสที่รัน PROCEDURE
IF(@month >=1 and @month <= 3)
BEGIN
SET @quater = 1
SET @s_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-01-01'), 23)
SET @e_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-03-31'), 23)
END
ELSE IF(@month >=4 and @month <= 6)
BEGIN
SET @quater = 2
SET @s_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-04-01'), 23)
SET @e_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-06-30'), 23)
END
ELSE IF(@month >=6 and @month <= 9)
BEGIN
SET @quater = 3
SET @s_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-07-01'), 23)
SET @e_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-09-30'), 23)
END
ELSE IF(@month >=10 and @month <= 12)
BEGIN
SET @quater = 4
SET @s_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-10-01'), 23)
SET @e_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-12-31'), 23)
END
--------------------

DECLARE cursor_name CURSOR FOR 
	SELECT BASIC_ID,CHAPA_CLOUD_ID FROM M_BASIC WHERE CHAPA_CLOUD_ID <> '000' AND DEP_STATUS = '1' ORDER BY CHAPA_CLOUD_ID

	OPEN cursor_name
	FETCH NEXT FROM cursor_name
	INTO @BASIC_ID,@CHAPA_CLOUD_ID

	-- Loop From Cursor
	WHILE (@@FETCH_STATUS = 0) 
	BEGIN 

SET @DBNAME='baac_chapa_'+@CHAPA_CLOUD_ID

-- --สมัครใหม่

SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_AGE_RANGE)

 SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE,quater,year_age) 
  SELECT
  ROW_NUMBER() OVER(ORDER BY (SELECT NULL))+'+@maxid+' AS report_id,
	(
		'''+@date_run+'''
	) AS date_run,
	(
		'''+@date_data+'''
	) AS date_data,
	(
		'''+@CHAPA_CLOUD_ID+'''
	) AS BAAC_CLOUD_ID,
	''2'' AS status_type,
	COUNT (*) c_member,
	gender,
	m_cate_id,
	(
		CASE
		WHEN (age <= 40) THEN
			1
		WHEN (age > 40 AND age <= 50) THEN
			2
		WHEN (age > 50 AND age <= 60) THEN
			3
		WHEN (age > 60 AND age <= 70) THEN
			4
		WHEN (age > 70 AND age <= 80) THEN
			5
		WHEN (age > 80 AND age <= 90) THEN
			6
		WHEN (age > 90) THEN
			7
		ELSE
			0
		END
	) age_group,
	'''+convert(VARCHAR(10),@quater)+''',
	'''+convert(VARCHAR(10),@S_YEAR)+'''
FROM
	(
		SELECT
			M_MEMBER.member_id,
			M_MEMBER.BIRTHDATE,
			M_MEMBER.out_date,
			M_MEMBER.gender,
			M_MEMBER.member_status,
			M_MEMBER.EFF_DATE,
			(
							CASE 
							WHEN M_MEMBER.m_cate_id IN(1,2) THEN
								(
									M_MEMBER.m_cate_id
								)
							ELSE
								3
							END
						) m_cate_id,
			DATEDIFF(
				YEAR,
				M_MEMBER.BIRTHDATE,
				(
					CASE member_status
					WHEN (1) THEN
						'''+@s_date+'''
					ELSE
						M_MEMBER.out_date
					END
				)
			) age
		FROM
			'+@DBNAME+'.dbo.M_MEMBER
		WHERE
			member_status + 0 IN (1, 2, 3, 5, 6)
      AND ISNULL(gender,0) > 0
	) AS tb_member
WHERE
	member_status > 0
AND EFF_DATE >= '''+@s_date+''' AND EFF_DATE <= '''+@e_date+'''
GROUP BY
	(
		CASE
		WHEN (age <= 40) THEN
			1
		WHEN (age > 40 AND age <= 50) THEN
			2
		WHEN (age > 50 AND age <= 60) THEN
			3
		WHEN (age > 60 AND age <= 70) THEN
			4
		WHEN (age > 70 AND age <= 80) THEN
			5
		WHEN (age > 80 AND age <= 90) THEN
			6
		WHEN (age > 90) THEN
			7
		ELSE
			0
		END
	),
	gender,
	m_cate_id'

EXEC sp_Executesql @SQLString 

--=========================================
--เสียชีวิต

SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_AGE_RANGE)

SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE,quater,year_age)
SELECT
ROW_NUMBER() OVER(ORDER BY (SELECT NULL))+'+@maxid+' AS report_id,
(
		'''+@date_run+'''
	) AS date_run,
	(
		'''+@date_data+'''
	) AS date_data,
	(
		'''+@CHAPA_CLOUD_ID+'''
	) AS BAAC_CLOUD_ID,
	''3'' AS status_type,
	COUNT (*) c_member,
	gender,m_cate_id,
	(
		CASE
		WHEN (age <= 40) THEN
			1
		WHEN (age > 40 AND age <= 50) THEN
			2
		WHEN (age > 50 AND age <= 60) THEN
			3
		WHEN (age > 60 AND age <= 70) THEN
			4
		WHEN (age > 70 AND age <= 80) THEN
			5
		WHEN (age > 80 AND age <= 90) THEN
			6
		WHEN (age > 90) THEN
			7
		ELSE
			0
		END
	) age_group,
	'''+convert(VARCHAR(10),@quater)+''',
	'''+convert(VARCHAR(10),@S_YEAR)+'''
FROM
	(
		SELECT
			M_MEMBER.member_id,
			M_MEMBER.BIRTHDATE,
			M_MEMBER.out_date,
			M_MEMBER.gender,
      (
							CASE 
							WHEN M_MEMBER.m_cate_id IN(1,2) THEN
								(
									M_MEMBER.m_cate_id
								)
							ELSE
								3
							END
						) m_cate_id,
			M_MEMBER.member_status,
			M_MEMBER.EFF_DATE,
			DATEDIFF(
				YEAR,
				M_MEMBER.BIRTHDATE,
				(
					CASE member_status
					WHEN (1) THEN
						'''+@e_date+'''
					ELSE
						M_MEMBER.out_date
					END
				)
			) age
		FROM
			'+@DBNAME+'.dbo.M_MEMBER
		WHERE
			member_status + 0 IN (1,2,3,5,6)
      AND ISNULL(gender,0) > 0
	) AS tb_member
WHERE
	member_status = 2
AND out_date >= '''+@s_date+''' AND out_date <= '''+@e_date+'''
GROUP BY
	(
		CASE
		WHEN (age <= 40) THEN
			1
		WHEN (age > 40 AND age <= 50) THEN
			2
		WHEN (age > 50 AND age <= 60) THEN
			3
		WHEN (age > 60 AND age <= 70) THEN
			4
		WHEN (age > 70 AND age <= 80) THEN
			5
		WHEN (age > 80 AND age <= 90) THEN
			6
		WHEN (age > 90) THEN
			7
		ELSE
			0
		END
	),
	gender,m_cate_id'

EXEC sp_Executesql @SQLString 

--=========================================
--ลาออก

SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_AGE_RANGE)

SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE,quater,year_age)
SELECT
  ROW_NUMBER() OVER(ORDER BY (SELECT NULL))+'+@maxid+' AS report_id,
	(
		'''+@date_run+'''
	) AS date_run,
	(
		'''+@date_data+'''
	) AS date_data,
	(
		'''+@CHAPA_CLOUD_ID+'''
	) AS BAAC_CLOUD_ID,
	''4'' AS status_type,
	COUNT (*) c_member,
	gender,
	m_cate_id,
	(
		CASE
		WHEN (age <= 40) THEN
			1
		WHEN (age > 40 AND age <= 50) THEN
			2
		WHEN (age > 50 AND age <= 60) THEN
			3
		WHEN (age > 60 AND age <= 70) THEN
			4
		WHEN (age > 70 AND age <= 80) THEN
			5
		WHEN (age > 80 AND age <= 90) THEN
			6
		WHEN (age > 90) THEN
			7
		ELSE
			0
		END
	) age_group,
	'''+convert(VARCHAR(10),@quater)+''',
	'''+convert(VARCHAR(10),@S_YEAR)+'''
FROM
	(
		SELECT
			M_MEMBER.member_id,
			M_MEMBER.BIRTHDATE,
			M_MEMBER.out_date,
			M_MEMBER.gender,
			(
							CASE 
							WHEN M_MEMBER.m_cate_id IN(1,2) THEN
								(
									M_MEMBER.m_cate_id
								)
							ELSE
								3
							END
						) m_cate_id,
			M_MEMBER.member_status,
			M_MEMBER.EFF_DATE,
			DATEDIFF(
				YEAR,
				M_MEMBER.BIRTHDATE,
				(
					CASE member_status
					WHEN (1) THEN
						'''+@e_date+'''
					ELSE
						M_MEMBER.out_date
					END
				)
			) age
		FROM
			'+@DBNAME+'.dbo.M_MEMBER
		WHERE
			member_status + 0 IN (1, 2, 3, 5, 6)
			AND ISNULL(gender,0) > 0
	) AS tb_member
WHERE
	member_status = 6
AND out_date >= '''+@s_date+''' AND out_date <= '''+@e_date+'''
GROUP BY
	(
		CASE
		WHEN (age <= 40) THEN
			1
		WHEN (age > 40 AND age <= 50) THEN
			2
		WHEN (age > 50 AND age <= 60) THEN
			3
		WHEN (age > 60 AND age <= 70) THEN
			4
		WHEN (age > 70 AND age <= 80) THEN
			5
		WHEN (age > 80 AND age <= 90) THEN
			6
		WHEN (age > 90) THEN
			7
		ELSE
			0
		END
	),
	gender,
	m_cate_id'

	EXEC sp_Executesql @SQLString

--=========================================
--พ้นสภาพ

SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_AGE_RANGE)

SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE,quater,year_age)
SELECT
  ROW_NUMBER() OVER(ORDER BY (SELECT NULL))+'+@maxid+' AS report_id,
	(
		'''+@date_run+'''
	) AS date_run,
	(
		'''+@date_data+'''
	) AS date_data,
	(
		'''+@CHAPA_CLOUD_ID+'''
	) AS BAAC_CLOUD_ID,
	''5'' AS status_type,
	COUNT (*) c_member,
	gender,
	m_cate_id,
	(
		CASE
		WHEN (age <= 40) THEN
			1
		WHEN (age > 40 AND age <= 50) THEN
			2
		WHEN (age > 50 AND age <= 60) THEN
			3
		WHEN (age > 60 AND age <= 70) THEN
			4
		WHEN (age > 70 AND age <= 80) THEN
			5
		WHEN (age > 80 AND age <= 90) THEN
			6
		WHEN (age > 90) THEN
			7
		ELSE
			0
		END
	) age_group,
	'''+convert(VARCHAR(10),@quater)+''',
	'''+convert(VARCHAR(10),@S_YEAR)+'''
FROM
	(
		SELECT
			M_MEMBER.member_id,
			M_MEMBER.BIRTHDATE,
			M_MEMBER.out_date,
			M_MEMBER.gender,
			(
							CASE 
							WHEN M_MEMBER.m_cate_id IN(1,2) THEN
								(
									M_MEMBER.m_cate_id
								)
							ELSE
								3
							END
						) m_cate_id,
			M_MEMBER.member_status,
			M_MEMBER.EFF_DATE,
			DATEDIFF(
				YEAR,
				M_MEMBER.BIRTHDATE,
				(
					CASE member_status
					WHEN (1) THEN
						'''+@e_date+'''
					ELSE
						M_MEMBER.out_date
					END
				)
			) age
		FROM
			'+@DBNAME+'.dbo.M_MEMBER
		WHERE
			member_status + 0 IN (1, 2, 3, 5, 6) 
			AND ISNULL(gender,0) > 0
	) AS tb_member
WHERE
	member_status = 3
AND out_date >= '''+@s_date+''' AND out_date <= '''+@e_date+'''
GROUP BY
	(
		CASE
		WHEN (age <= 40) THEN
			1
		WHEN (age > 40 AND age <= 50) THEN
			2
		WHEN (age > 50 AND age <= 60) THEN
			3
		WHEN (age > 60 AND age <= 70) THEN
			4
		WHEN (age > 70 AND age <= 80) THEN
			5
		WHEN (age > 80 AND age <= 90) THEN
			6
		WHEN (age > 90) THEN
			7
		ELSE
			0
		END
	),
	gender,
	m_cate_id'

EXEC sp_Executesql @SQLString

--=====================================

-- --ยอดยกมา 
SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_AGE_RANGE)

SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE,quater,year_age)
SELECT
  ROW_NUMBER() OVER(ORDER BY (SELECT NULL))+'+@maxid+' AS report_id,
	(
		'''+@date_run+'''
	) AS date_run,
	(
		'''+@date_data+'''
	) AS date_data,
	(
		'''+@CHAPA_CLOUD_ID+'''
	) AS BAAC_CLOUD_ID,
	''1'' AS status_type,
	ABS(
		(
			CASE
			WHEN tb_member_all.c_member IS NULL THEN
				0
			ELSE
				tb_member_all.c_member
			END
		) - (
			CASE
			WHEN tb_member_all_out.c_member IS NULL THEN
				0
			ELSE
				tb_member_all_out.c_member
			END
		)
	) AS total_member,
	tb_member_all.gender,
	tb_member_all.m_cate_id,
	tb_member_all.age_group,
	'''+convert(VARCHAR(10),@quater)+''',
	'''+convert(VARCHAR(10),@S_YEAR)+'''
FROM
	(
		SELECT
			COUNT (*) c_member,
			gender,
			m_cate_id,
			(
				CASE
				WHEN (age <= 40) THEN
					1
				WHEN (age > 40 AND age <= 50) THEN
					2
				WHEN (age > 50 AND age <= 60) THEN
					3
				WHEN (age > 60 AND age <= 70) THEN
					4
				WHEN (age > 70 AND age <= 80) THEN
					5
				WHEN (age > 80 AND age <= 90) THEN
					6
				WHEN (age > 90) THEN
					7
				ELSE
					0
				END
			) age_group
		FROM
			(
				SELECT
					M_MEMBER.member_id,
					M_MEMBER.BIRTHDATE,
					M_MEMBER.out_date,
					M_MEMBER.gender,
					(
							CASE 
							WHEN M_MEMBER.m_cate_id IN(1,2) THEN
								(
									M_MEMBER.m_cate_id
								)
							ELSE
								3
							END
						) m_cate_id,
					M_MEMBER.member_status,
					M_MEMBER.EFF_DATE,
					DATEDIFF(
						YEAR,
						M_MEMBER.BIRTHDATE,
						(
							CASE member_status
							WHEN (1) THEN
								(
									'''+@s_date+'''
								)
							ELSE
								M_MEMBER.out_date
							END
						)
					) age
				FROM
					'+@DBNAME+'.dbo.M_MEMBER
				WHERE
					member_status + 0 IN (1, 2, 3, 5, 6)
          AND ISNULL(gender,0) > 0
			) AS tb_member
		WHERE
			member_status > 0
		AND EFF_DATE < (
			'''+@s_date+'''
		)
		GROUP BY
			(
				CASE
				WHEN (age <= 40) THEN
					1
				WHEN (age > 40 AND age <= 50) THEN
					2
				WHEN (age > 50 AND age <= 60) THEN
					3
				WHEN (age > 60 AND age <= 70) THEN
					4
				WHEN (age > 70 AND age <= 80) THEN
					5
				WHEN (age > 80 AND age <= 90) THEN
					6
				WHEN (age > 90) THEN
					7
				ELSE
					0
				END
			),
			gender,
			m_cate_id
	) AS tb_member_all 
LEFT JOIN (
	SELECT
		COUNT (*) c_member,
		gender,
		m_cate_id,
		(
			CASE
			WHEN (age <= 40) THEN
				1
			WHEN (age > 40 AND age <= 50) THEN
				2
			WHEN (age > 50 AND age <= 60) THEN
				3
			WHEN (age > 60 AND age <= 70) THEN
				4
			WHEN (age > 70 AND age <= 80) THEN
				5
			WHEN (age > 80 AND age <= 90) THEN
				6
			WHEN (age > 90) THEN
				7
			ELSE
				0
			END
		) age_group
	FROM
		(
			SELECT
				M_MEMBER.member_id,
				M_MEMBER.BIRTHDATE,
				M_MEMBER.out_date,
				M_MEMBER.gender,
				(
							CASE 
							WHEN M_MEMBER.m_cate_id IN(1,2) THEN
								(
									M_MEMBER.m_cate_id
								)
							ELSE
								3
							END
						) m_cate_id,
				M_MEMBER.member_status,
				M_MEMBER.EFF_DATE,
				DATEDIFF(
					YEAR,
					M_MEMBER.BIRTHDATE,
					(
						CASE member_status
						WHEN (1) THEN
							(
								'''+@s_date+'''
							)
						ELSE
							M_MEMBER.out_date
						END
					)
				) age
			FROM
				'+@DBNAME+'.dbo.M_MEMBER
			WHERE
				member_status + 0 IN (1, 2, 3, 5, 6)
        AND ISNULL(gender,0) > 0
		) AS tb_member
	WHERE
		member_status > 1
	AND out_date < (
		'''+@s_date+'''
	)
	GROUP BY
		(
			CASE
			WHEN (age <= 40) THEN
				1
			WHEN (age > 40 AND age <= 50) THEN
				2
			WHEN (age > 50 AND age <= 60) THEN
				3
			WHEN (age > 60 AND age <= 70) THEN
				4
			WHEN (age > 70 AND age <= 80) THEN
				5
			WHEN (age > 80 AND age <= 90) THEN
				6
			WHEN (age > 90) THEN
				7
			ELSE
				0
			END
		),
		gender,
		m_cate_id
) AS tb_member_all_out  ON tb_member_all.gender = tb_member_all_out.gender
AND tb_member_all.age_group = tb_member_all_out.age_group
AND tb_member_all.m_cate_id = tb_member_all_out.m_cate_id'

EXEC sp_Executesql @SQLString 

-- --=========================================

		FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name

	--- ลบข้อมูลออกจาก quater ก่อนหน้า
	SET @SQLString ='DELETE FROM M_AGE_RANGE WHERE quater = '''+convert(VARCHAR(10),@quater)+''' 
	AND DATE_RUN <> '''+convert(VARCHAR(10),@date_run)+''' AND year_age =  '''+convert(VARCHAR(10),@S_YEAR)+''' '
	EXEC sp_Executesql @SQLString
END