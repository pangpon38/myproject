ALTER PROCEDURE [dbo].[test_insert]
AS
BEGIN
DECLARE @SQLString NVARCHAR(MAX)
DECLARE @BASIC_ID INT
DECLARE @CHAPA_CLOUD_ID VARCHAR(3)
DECLARE @DBNAME VARCHAR(100)
DECLARE @maxid VARCHAR(50)
DECLARE @date_run VARCHAR(10)
DECLARE @date_data VARCHAR(10)

SET @SQLString=N''
SET @date_run = convert(VARCHAR(10), getdate(), 23)
SET @date_data = convert(VARCHAR(10), getdate()-1, 23)

DECLARE cursor_name CURSOR FOR 
	SELECT BASIC_ID,CHAPA_CLOUD_ID FROM M_BASIC WHERE CHAPA_CLOUD_ID<>'000' ORDER BY CHAPA_CLOUD_ID

	OPEN cursor_name
	FETCH NEXT FROM cursor_name
	INTO @BASIC_ID,@CHAPA_CLOUD_ID

	-- Loop From Cursor
	WHILE (@@FETCH_STATUS = 0) 
	BEGIN 

SET @DBNAME='baac_chapa_'+@CHAPA_CLOUD_ID
SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_AGE_RANGE)

-- --ยอดยกมา
SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE)
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
	tb_member_all.age_group
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
									CONVERT (DATE, getdate() - 2, 103)
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
			CONVERT (DATE, getdate() - 2, 103)
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
								CONVERT (DATE, getdate() - 2, 103)
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
		CONVERT (DATE, getdate() - 2, 103)
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
-- --สมัครใหม่

SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_AGE_RANGE)

 SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE) 
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
	) age_group
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
						'''+@date_data+'''
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
AND EFF_DATE = '''+@date_data+'''
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

SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE)
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
						'''+@date_data+'''
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
	member_status = 2
AND out_date = '''+@date_data+'''
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

SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE)
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
						'''+@date_data+'''
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
AND out_date = '''+@date_data+'''
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

SET @SQLString = 'INSERT INTO M_AGE_RANGE (report_id,date_run,date_data,CHAPA_CLOUD_ID,TYPE_STATUS,COUNTS,GENDER,TYPE_MEMBER,AGE_RANGE)
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
						'''+@date_data+'''
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
AND out_date = '''+@date_data+'''
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

		FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name
END