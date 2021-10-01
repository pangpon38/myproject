SELECT
	(
		CONVERT (DATE, getdate(), 103)
	) AS date_run,
	(
		CONVERT (DATE, getdate() - 1, 103)
	) AS date_data,
	(
		SELECT
			baac_cloud_id
		FROM
			SYS_BASIC
	) AS BAAC_CLOUD_ID,
	'2' AS type_status,
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
			M_MEMBER.m_cate_id,
			DATEDIFF(
				YEAR,
				M_MEMBER.BIRTHDATE,
				(
					CASE member_status
					WHEN (1) THEN
						(
							CONVERT (DATE, getdate() - 1, 103)
						)
					ELSE
						M_MEMBER.out_date
					END
				)
			) age
		FROM
			M_MEMBER
		WHERE
			member_status + 0 IN (1, 2, 3, 5, 6)
			AND ISNULL(gender,0) > 0
	) AS tb_member
WHERE
	member_status > 0
AND EFF_DATE = (
	CONVERT (DATE, getdate() - 1, 103)
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