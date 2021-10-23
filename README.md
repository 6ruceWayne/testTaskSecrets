TODO:

1) Download the project to your PC

2) Open command line and write 'composer install' and press Enter. Wait until instalation is finished

3) Open .env file. You need to change two lines: 
3.1) DataBase link - substitute login, password, data_base_name with your own
(so you need to have MySQL credential but no need to create DataBase - it will be made in next steps by Symfony)
3.2) Open command line and write 'php bin/console encrypt:genkey'. This will generate encription key. Copy it and write into ENCRYPT_KEY variable.
WARNING: Encryption key is a way that application saves the data. Loosing this key, revolking it etc. will cause data lost. Backups strongly recomended.

4) Write in command line: 'php bin/console doctrine:database:create'. Then 'php bin/console doctrine:migrations:migrate' - that will make dataBase and table

5) Set up server with command 'php bin/console server:run'

6) Have fun :)

Small description:
The site saves your secrets and passphrase in encrypted formats using 'specshaper/encrypt-bundle' lib.
In the task description it was clearly writen to create special link so no general links for passphrase form exists.
