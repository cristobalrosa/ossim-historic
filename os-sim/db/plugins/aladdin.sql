-- Aladdin eSafe Gateway
-- plugin_id: 1566

DELETE FROM plugin WHERE id = "1566";
DELETE FROM plugin_sid where plugin_id = "1566";

INSERT INTO plugin (id, type, name, description) VALUES (1566, 1, 'aladdin', 'Aladdin eSafe Gateway');
INSERT INTO plugin_sid (plugin_id, sid, category_id, class_id, name, priority, reliability) VALUES (1566, 1, NULL, NULL, 'Aladdin: File Blocked', 1, 3);
