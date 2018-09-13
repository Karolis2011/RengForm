INSERT INTO `User` (`id`, `username`, `password`, `email`)
VALUES
  ('5f26cf7f-30a0-11e8-90c6-080027c702a7', 'admin', '$2y$13$YvkFxKWrM.9ulSyWE4msjee/Om3s0qvzXbd9K6bmArhKH2CG6ATeC', 'asdf@asdf.dsf');

INSERT INTO `FormConfig` (`id`, `owner_id`, `title`, `description`, `config`, `created`, `type`)
VALUES
  ('ebe13752-384c-11e8-9074-080027c702a7', '5f26cf7f-30a0-11e8-90c6-080027c702a7', 'selects', '', '[{\"type\":\"radio-group\",\"required\":true,\"label\":\"Radio Group\",\"values\":[{\"label\":\"Option 1\",\"value\":\"option-1\"},{\"label\":\"Option 2\",\"value\":\"option-2\"},{\"label\":\"Option 3\",\"value\":\"option-3\"}],\"name\":\"radio-group\"},{\"type\":\"radio-group\",\"label\":\"Radio Group2\",\"values\":[{\"label\":\"Option 1\",\"value\":\"option-1\"},{\"label\":\"Option 2\",\"value\":\"option-2\"},{\"label\":\"Option 3\",\"value\":\"option-3\"}],\"name\":\"radio-group2\"}]', '2018-04-06 18:58:29', 'simple');

INSERT INTO `Event` (`id`, `owner_id`, `title`, `description`, `place`, `duration`, `capacity`, `created`, `formConfig_id`)
VALUES
  ('09606e22-3851-11e8-9074-080027c702a7', '5f26cf7f-30a0-11e8-90c6-080027c702a7', 'selects', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'Griciupio g. 13, Kaunas', '01:00:00', NULL, '2018-04-06 19:27:57', 'ebe13752-384c-11e8-9074-080027c702a7');

INSERT INTO `EventTime` (`id`, `event_id`, `startTime`, `entries`)
VALUES
  ('367c42c6-3863-11e8-9074-080027c702a7', '09606e22-3851-11e8-9074-080027c702a7', '2018-04-30 15:06:00', 0),
  ('b8080ef8-387a-11e8-9074-080027c702a7', '09606e22-3851-11e8-9074-080027c702a7', '2018-04-07 17:54:00', 0);

INSERT INTO `MultiEvent` (`id`, `owner_id`, `title`, `description`, `date`, `endDate`, `created`)
VALUES
  ('ae9f01c4-38bb-11e8-9074-080027c702a7', '5f26cf7f-30a0-11e8-90c6-080027c702a7', 'sdfafd', 'asdf', '2018-04-08 22:00:00', '2018-04-08 22:00:00', '2018-04-08 19:00:42');

INSERT INTO `Category` (`id`, `event_id`, `title`, `description`, `created`, `orderNo`)
VALUES
  ('89cacd61-38c0-11e8-9074-080027c702a7', 'ae9f01c4-38bb-11e8-9074-080027c702a7', 'a', 'sadf', '2018-04-08 19:35:28', 2);

INSERT INTO `Workshop` (`id`, `category_id`, `title`, `description`, `place`, `duration`, `capacity`, `created`, `formConfig_id`)
VALUES
  ('9b65a2ab-38c0-11e8-9074-080027c702a7', '89cacd61-38c0-11e8-9074-080027c702a7', 'one', '', 'Griciupio g. 13, Kaunas', '01:00:00', NULL, '2018-04-08 19:35:58', 'ebe13752-384c-11e8-9074-080027c702a7');

INSERT INTO `WorkshopTime` (`id`, `workshop_id`, `startTime`, `entries`)
VALUES
  ('9b65be4a-38c0-11e8-9074-080027c702a7', '9b65a2ab-38c0-11e8-9074-080027c702a7', '2018-04-08 22:00:00', 0);
