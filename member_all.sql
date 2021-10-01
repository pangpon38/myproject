WITH tb_member_all AS (
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
				M_MEMBER.m_cate_id,
				M_MEMBER.member_status,
				M_MEMBER.EFF_DATE,
				DATEDIFF(
					YEAR,
					M_MEMBER.BIRTHDATE,
					(
						CASE member_status
						WHEN (1) THEN
							(CONVERT(date, getdate()-2, 103))
						ELSE
							M_MEMBER.out_date
						END
					)
				) age
			FROM
				M_MEMBER
			WHERE
				member_status + 0 IN (1, 2, 3, 5, 6)
		) AS tb_member
	WHERE
		member_status > 0
	AND EFF_DATE < (CONVERT(date, getdate()-2, 103))
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
),
 tb_member_all_out AS (
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
				M_MEMBER.m_cate_id,
				M_MEMBER.member_status,
				M_MEMBER.EFF_DATE,
				DATEDIFF(
					YEAR,
					M_MEMBER.BIRTHDATE,
					(
						CASE member_status
						WHEN (1) THEN
							(CONVERT(date, getdate()-2, 103))
						ELSE
							M_MEMBER.out_date
						END
					)
				) age
			FROM
				M_MEMBER
			WHERE
				member_status + 0 IN (1, 2, 3, 5, 6)
		) AS tb_member
	WHERE
		member_status > 1
	AND out_date < (CONVERT(date, getdate()-2, 103))
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
) SELECT
(CONVERT(date, getdate(), 103)) as date_run,(CONVERT(date, getdate()-2, 103)) as date_data,
	(
		SELECT
			baac_cloud_id
		FROM
			SYS_BASIC
	) AS BAAC_CLOUD_ID,'1' as type_status,
	ABS(
		(
			CASE
			WHEN a.c_member IS NULL THEN
				0
			ELSE
				a.c_member
			END
		) - (
			CASE
			WHEN b.c_member IS NULL THEN
				0
			ELSE
				b.c_member
			END
		)
	) AS total_member,
	a.gender,
	a.age_group,
	a.m_cate_id
FROM
	tb_member_all a
LEFT JOIN tb_member_all_out b ON a.gender = b.gender
AND a.age_group = b.age_group
AND a.m_cate_id = b.m_cate_id