## workflow
SoFab

git checkout master

git fetch upstream

cd newscoop
rm composer.lock composer.json

git merge upstream/master


stefan@EeePC:$ sudo php newscoop/application/console newscoop:install --database_name=newscoop_dev --database_password=* --database_override --alias=newscoop --fix

sudo chmod -R 777 newscoop/cache/


INSERT INTO `liveuser_users` (`Id`, `KeyId`, `Name`, `UName`, `Password`, `EMail`, `Reader`, `fk_user_type`, `City`, `StrAddress`, `State`, `CountryCode`, `Phone`, `Fax`, `Contact`, `Phone2`, `Title`, `Gender`, `Age`, `PostalCode`, `Employer`, `EmployerType`, `Position`, `Interests`, `How`, `Languages`, `Improvements`, `Pref1`, `Pref2`, `Pref3`, `Pref4`, `Field1`, `Field2`, `Field3`, `Field4`, `Field5`, `Text1`, `Text2`, `Text3`, `time_updated`, `time_created`, `lastLogin`, `isActive`, `password_reset_token`, `role_id`, `last_name`, `status`, `is_admin`, `is_public`, `points`, `image`, `subscriber`, `author_id`, `indexed`) VALUES
(1, NULL, 'Administrator (don''t delete)', 'admin', 'sha1$2lUvd7x2NakO$a11279c6e0dd5058fbbeedea226c3c7ee9253de6', 'admin@admin.com', 'N', 1, '', '', '', 'AD', '', '', '', '', 'Mr.', 'M', '0-17', '', '', 'Corporate', '', '', '', '', '', 'N', 'N', 'N', 'N', '', '', '', '', '', '', '', '', '2014-01-21 11:55:37', '0000-00-00 00:00:00', '2013-11-21 14:45:55', 1, NULL, 6, '', 1, 1, 0, 0, 'user_placeholder_1.png', NULL, NULL, '2013-11-21 14:48:02');


chmod o+w composer.*

http://newscoop/admin/plugins
facebook-newscoop-bundle

itbsstefan/facebook-newscoop-bundle
Newscoop/newscoop/plugins/Newscoop/FacebookNewscoopBundle/


for help
php newscoop/application/console

to install plugin from cli
php newscoop/application/console plugins:install newscoop/articles-calendar-plugin



stefan@EeePC:$ php newscoop/application/console plugins:install newscoop/articles-calendar-plugin
./composer.json has been updated
Loading composer repositories with package information
Updating dependencies
  - Installing newscoop/articles-calendar-plugin (dev-master e373ebe)
    Downloading: 100%         

Writing lock file
Generating autoload files
remove /home/stefan/1Projekte/SF/Newscoop/newscoop/cache/*
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/86/43/20d428c14c221db0ab26cd8bfecf1244819b1c11bb88fde3e655d8b36e3d.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/b1/ce/7e7df4d7287741b99bd87e02500cc45a09e46e42af30fbd970fa477b5938.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/e0/b6/f6a2db3f4f2b22f243cca044e6f5079549d01591c727c14fbc74cbb230a9.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/d0/07/a3caabbdd49cb85498922d97cfec3dff31211810b7d8a50157599e7c1d2d.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/00/0a/051a2495c451fe623928c1c4933c9030ee00ba73067e5e577933909d94a3.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/fc/b5/14a104701164a352373870446aa4fe561ef1d3b42fb8fca697e5444a2621.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/f5/9e/d863a08e67bdc5942ef74e501956a3306df48a8f94214b25053d0038b8de.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/a7/73/9dea6076329b6b7b500ddf7d2f11ac533cc61a2b44cbbbe4f525e5a444b1.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/twig/b8/53/9eb6869af2dcd3d962bf62eae6bd9daa97a6d4b5f807584db2c95272d24b.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/fosJsRouting/data.json“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/translations/catalogue.de.php“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/bazinga_expose_translation/messages.de.js“ ist nicht möglich: Keine Berechtigung
rm: das Entfernen von »/home/stefan/1Projekte/SF/Newscoop/newscoop/cache/prod/fos_rest/allowed_methods.cache.php“ ist nicht möglich: Keine Berechtigung
We just fired: "plugin.install" event
We just fired: "plugin.install.newscoop_articles_calendar_plugin" event
Plugin newscoop/articles-calendar-plugin is installed!





