SELECT
	ROW_NUMBER () OVER (ORDER BY member_id ASC) AS member_pk,
	*
FROM
	(
		SELECT
			a.chg_id,
			M_MEMBER.id_card_no,
			a.update_by AS approve_name,
			(
				SELECT
					prefix_name
				FROM
					prefix
				WHERE
					prefix.prefix_id = M_MEMBER.prefix_id
			) AS prefix_name,
			M_MEMBER.fname,
			M_MEMBER.lname,
			M_MEMBER.m_cate_id,
			M_MEMBER.member_no,
			(
				SELECT
					prefix_name
				FROM
					prefix
				WHERE
					prefix.prefix_id = M_CHG_NAME.prefix_id_old
			) + ' ' + M_CHG_NAME.fname_old + ' ' + M_CHG_NAME.lname_old AS data_old,
			(
				SELECT
					prefix_name
				FROM
					prefix
				WHERE
					prefix.prefix_id = M_CHG_NAME.prefix_id_new
			) + ' ' + M_CHG_NAME.fname_new + ' ' + M_CHG_NAME.lname_new AS data_new,
			M_CHG_NAME.approve_date,
			'เปลี่ยน ชื่อ - นามสกุล' AS type_chg,
			a.remark_other,
			M_CHG_NAME.type_chg_id,
			M_MEMBER.member_id
		FROM
			M_CHG_PROFILE a
		JOIN M_MEMBER ON M_MEMBER.member_id = a.member_id
		JOIN M_CHG_NAME ON M_CHG_NAME.chg_id = a.chg_id
		AND a.approve_status = '1'
		UNION ALL
			SELECT
				b.chg_id,
				M_MEMBER.id_card_no,
				b.update_by AS approve_name,
				(
					SELECT
						prefix_name
					FROM
						prefix
					WHERE
						prefix.prefix_id = M_MEMBER.prefix_id
				) AS prefix_name,
				M_MEMBER.fname,
				M_MEMBER.lname,
				M_MEMBER.m_cate_id,
				M_MEMBER.member_no,
				'' AS data_old,
				'' AS data_new,
				b.approve_date,
				'เปลี่ยนผู้รับผลประโยชน์' AS type_chg,
				b.remark_other,
				4 AS type_chg_id,
				M_MEMBER.member_id
			FROM
				M_CHG_PROFILE b
			JOIN M_MEMBER ON M_MEMBER.member_id = b.member_id
			WHERE
				b.chg_code = 2
			AND b.approve_status = '1'
			UNION ALL
				SELECT
					c.chg_id,
					M_MEMBER.id_card_no,
					c.update_by AS approve_name,
					(
						SELECT
							prefix_name
						FROM
							prefix
						WHERE
							prefix.prefix_id = M_MEMBER.prefix_id
					) AS prefix_name,
					M_MEMBER.fname,
					M_MEMBER.lname,
					M_MEMBER.m_cate_id,
					M_MEMBER.member_no,
					dbo.check_data (
						'เลขที่',
						M_CHG_ADDS.home_no_old
					) + dbo.check_data (
						'ม.',
						M_CHG_ADDS.moo_no_old
					) + dbo.check_data (
						'ช.',
						M_CHG_ADDS.soi_name_old
					) + dbo.check_data (
						'ถ.',
						M_CHG_ADDS.road_name_old
					) + dbo.check_data (
						'จ.',
						(
							SELECT
								province_name
							FROM
								province
							WHERE
								province_code = M_CHG_ADDS.prov_id_old
						)
					) + dbo.check_data (
						'อ.',
						(
							SELECT
								amphur_name
							FROM
								amphur
							WHERE
								province_code = M_CHG_ADDS.prov_id_old
							AND amphur_code = M_CHG_ADDS.amp_id_old
						)
					) + dbo.check_data (
						'ต.',
						(
							SELECT
								tambon_name
							FROM
								tambon
							WHERE
								province_code = M_CHG_ADDS.prov_id_old
							AND amphur_code = M_CHG_ADDS.amp_id_old
							AND tambon_code = M_CHG_ADDS.tam_id_old
						)
					) + ' ' + dbo.check_data (
						'ไปรษณีย์',
						postcode_old
					) + dbo.check_data (
						'โทร',
						M_CHG_ADDS.tel_home_old
					) + dbo.check_data (
						'มือถือ',
						M_CHG_ADDS.mobile_old
					) AS data_old,
					dbo.check_data (
						'เลขที่',
						M_CHG_ADDS.home_no_new
					) + dbo.check_data (
						'ม.',
						M_CHG_ADDS.moo_no_new
					) + dbo.check_data (
						'ช.',
						M_CHG_ADDS.soi_name_new
					) + dbo.check_data (
						'ถ.',
						M_CHG_ADDS.road_name_new
					) + dbo.check_data (
						'จ.',
						(
							SELECT
								province_name
							FROM
								province
							WHERE
								province_code = M_CHG_ADDS.prov_id_new
						)
					) + dbo.check_data (
						'อ.',
						(
							SELECT
								amphur_name
							FROM
								amphur
							WHERE
								province_code = M_CHG_ADDS.prov_id_new
							AND amphur_code = M_CHG_ADDS.amp_id_new
						)
					) + dbo.check_data (
						'ต.',
						(
							SELECT
								tambon_name
							FROM
								tambon
							WHERE
								province_code = M_CHG_ADDS.prov_id_new
							AND amphur_code = M_CHG_ADDS.amp_id_new
							AND tambon_code = M_CHG_ADDS.tam_id_new
						)
					) + ' ' + dbo.check_data (
						'ไปรษณีย์',
						postcode_new
					) + dbo.check_data (
						'โทร',
						M_CHG_ADDS.tel_home_new
					) + dbo.check_data (
						'มือถือ',
						M_CHG_ADDS.mobile_new
					) AS data_new,
					M_CHG_ADDS.approve_date,
					'เปลี่ยน ที่อยู่' AS type_chg,
					c.remark_other,
					M_CHG_ADDS.type_chg_id,
					M_MEMBER.member_id
				FROM
					M_CHG_PROFILE c
				JOIN M_MEMBER ON M_MEMBER.member_id = c.member_id
				JOIN M_CHG_ADDS ON M_CHG_ADDS.chg_id = c.chg_id
				AND c.approve_status = '1'
				UNION ALL
					SELECT
						d.chg_id,
						M_MEMBER.id_card_no,
						d.update_by AS approve_name,
						(
							SELECT
								prefix_name
							FROM
								prefix
							WHERE
								prefix.prefix_id = M_MEMBER.prefix_id
						) AS prefix_name,
						M_MEMBER.fname,
						M_MEMBER.lname,
						M_MEMBER.m_cate_id,
						M_MEMBER.member_no,
						CASE
					WHEN M_CHG_MARRY.marry_status_old = 1 THEN
						'โสด'
					WHEN M_CHG_MARRY.marry_status_old = 2 THEN
						'สมรส'
					ELSE
						'หย่า'
					END AS data_old,
					CASE
				WHEN M_CHG_MARRY.marry_status_new = 1 THEN
					'โสด'
				WHEN M_CHG_MARRY.marry_status_new = 2 THEN
					'สมรส'
				ELSE
					'หย่า'
				END AS data_new,
				M_CHG_MARRY.approve_date,
				'เปลี่ยนสถานะภาพ' AS type_chg,
				d.remark_other,
				M_CHG_MARRY.type_chg_id,
				M_MEMBER.member_id
			FROM
				M_CHG_PROFILE d
			JOIN M_MEMBER ON M_MEMBER.member_id = d.member_id
			JOIN M_CHG_MARRY ON M_CHG_MARRY.chg_id = d.chg_id
			AND d.approve_status = '1'
			UNION ALL
				SELECT
					d.chg_id,
					M_MEMBER.id_card_no,
					d.update_by AS approve_name,
					(
						SELECT
							prefix_name
						FROM
							prefix
						WHERE
							prefix.prefix_id = M_MEMBER.prefix_id
					) AS prefix_name,
					M_MEMBER.fname,
					M_MEMBER.lname,
					M_MEMBER.m_cate_id,
					M_MEMBER.member_no,
					(
						ISNULL(M_CHG_BANK.bank_no_old, '')
					) AS data_old,
					(
						ISNULL(M_CHG_BANK.bank_no_new, '')
					) AS data_new,
					M_CHG_BANK.approve_date,
					'เปลี่ยนแปลงเลขที่บัญชี' AS type_chg,
					d.remark_other,
					M_CHG_BANK.type_chg_id,
					M_MEMBER.member_id
				FROM
					M_CHG_PROFILE d
				JOIN M_MEMBER ON M_MEMBER.member_id = d.member_id
				JOIN M_CHG_BANK ON M_CHG_BANK.chg_id = d.chg_id
				AND d.approve_status = '1'
				UNION ALL
					SELECT
						d.chg_id,
						M_MEMBER.id_card_no,
						d.update_by AS approve_name,
						(
							SELECT
								prefix_name
							FROM
								prefix
							WHERE
								prefix.prefix_id = M_MEMBER.prefix_id
						) AS prefix_name,
						M_MEMBER.fname,
						M_MEMBER.lname,
						M_MEMBER.m_cate_id,
						M_MEMBER.member_no,
						(
							'ประเภทสมาชิก: ' + ISNULL(M_TYPE.m_cate_name, '') + ' ' + 'รหัสอำเภอ: ' + ISNULL(
								M_CHG_OTHER.baac_aumphur_old,
								''
							) + ' ' + 'รหัสตำบล: ' + ISNULL(
								M_CHG_OTHER.baac_tambon_old,
								''
							) + ' ' + 'กลุ่ม: ' + ISNULL(
								M_CHG_OTHER.baac_group_old,
								''
							) + ' ' + 'เลขทะเบียน: ' + ISNULL(M_CHG_OTHER.baac_no_old, '') + ' ' + 'เลขCIF: ' + ISNULL(
								M_CHG_OTHER.baac_cif_old,
								''
							)
						) AS data_old,
						(
							'ประเภทสมาชิก: ' + ISNULL(M_TYPE.m_cate_name, '') + ' ' + 'รหัสอำเภอ: ' + ISNULL(
								M_CHG_OTHER.baac_aumphur_new,
								''
							) + ' ' + 'รหัสตำบล: ' + ISNULL(
								M_CHG_OTHER.baac_tambon_new,
								''
							) + ' ' + 'กลุ่ม: ' + ISNULL(
								M_CHG_OTHER.baac_group_new,
								''
							) + ' ' + 'เลขทะเบียน: ' + ISNULL(M_CHG_OTHER.baac_no_new, '') + ' ' + 'เลขCIF: ' + ISNULL(
								M_CHG_OTHER.baac_cif_new,
								''
							)
						) AS data_new,
						M_CHG_OTHER.approve_date,
						'เปลี่ยนแปลงอื่นๆ' AS type_chg,
						d.remark_other,
						M_CHG_OTHER.type_chg_id,
						M_MEMBER.member_id
					FROM
						M_CHG_PROFILE d
					JOIN M_MEMBER ON M_MEMBER.member_id = d.member_id
					JOIN M_CHG_OTHER ON M_CHG_OTHER.chg_id = d.chg_id
					JOIN M_TYPE ON M_CHG_OTHER.m_cate_id_new = M_TYPE.m_cate_id
					OR M_CHG_OTHER.m_cate_id_old = M_TYPE.m_cate_id
					AND d.approve_status = '1'
	) AS tb