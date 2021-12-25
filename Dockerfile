FROM mediawiki:1.37

COPY ./ /var/www/html/extensions/ThisIsNotAWiki
COPY ThisIsNotAWikiSettings.php /var/www/html/

COPY run /usr/local/bin/
CMD ["/usr/local/bin/run"]
