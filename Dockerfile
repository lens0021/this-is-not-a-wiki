FROM mediawiki:1.37

COPY extension/ /var/www/html/extensions/ThisIsNotAWiki
COPY NoWikiSettings.php /var/www/html/

COPY run /usr/local/bin/
CMD ["/usr/local/bin/run"]
