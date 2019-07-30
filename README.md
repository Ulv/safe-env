# Usage

Solves:

* [CWE-256: Unprotected Storage of Credentials](https://cwe.mitre.org/data/definitions/256.html)
* [Top 10-2017 A2-Broken Authentication](https://www.owasp.org/index.php/Top_10-2017_A2-Broken_Authentication)


## Initial setup
* Generate encryption key and save it to file as following
```shell script
php vendor/bin/generate-defuse-key > /etc/defuse-key.asc
```
* Set key file path in environment variable (or .env.* file)
```shell script
DEFUSE_KEY_FILE=/etc/defuse-key.asc
```

## Create and store encrypted credentials

* Encrypt user name with stored encryption key (command will ask for input)
```shell script
php ./bin/console lead:encrypt:string
```

The output will be like:
```shell script
The encrypted string is:
def5020060ddd9429cd02129adbf643bb58ea6fb356c95ceb19640af5bf4763c775fcf20c19bb8f1169e16f8906af76879357651a3ec410b2ff4786952884c125d9f8eabab75b0e5ca4d0e73230d0296380b458b19d01e02545c054fe61cdb6b2e
```

* Repeat step for database password

* Copy and paste encrypted values in DATABASE_URL environment value (DSN)
Will be something like:
```shell script
DATABASE_URL=pgsql://def502002c10a0c171d73c04b3665ef0672aefc9b978b77a366ad5ce32e100a50300e746f4af425369f50a79fefbd89112637c5b448881b531cb52b02830ab526d09e94f924d0e8145dba88cc7eb694009d8dcde199e58f40df74e1f:def502009411dff01adc99de71401da4f889cc27e93d24d74424b38682d696a0fe8eed7117992c9a3a29a9e0d424f7b57c84225d99049a3b035e79515522efdb8bdf8f1a54eb76893b1e1984b9070b8d16b760d8f565d681ad8d522c96ab16916d@172.16.238.13:5432/payment
```

## Use encrypted credentials for DB connection

In doctrine's config use "decrypt_resolve" env processor:
```yaml
    url: '%env(decrypt_resolve:DATABASE_URL)%'
```
