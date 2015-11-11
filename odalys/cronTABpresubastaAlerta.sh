# Configuración del crontab a
#yiic sitemap index --type=News --limit=5s
#http://www.yiiframework.com/doc/guide/1.1/en/topics.console
#crontab -e 00 * * * * /usr/local/bin/php  /home/odalyscs/public_html/prueba/odalys/protected/yiic sitemap alertaPresubasta
crontab -e 00 * * * * php  /home/odalyscs/public_html/prueba/odalys/protected/yiic sitemap alertaPresubasta
