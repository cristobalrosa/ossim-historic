-- optener antispam
-- plugin_id: 1563

DELETE FROM plugin WHERE id = "1563";
DELETE FROM plugin_sid where plugin_id = "1563";

insert into plugin values(1563, 1, "optenet antispam", "optenet antispam");
insert into plugin_sid (plugin_id, sid, category_id, class_id, reliability, priority, name) values(1563, 1, null, null, 1, 1, "optenet: spam detected");
