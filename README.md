# SAFETECHio PHP FIDO2 Example

## Libraries

For details of the source libraries see [dedicated repo](https://github.com/SAFETECHio/FIDO2_SERVER_Libraries).

## Getting Started

If you don't have access to a running configured php server no problem you can use the docker container provided.

Open a new terminal window and navigate to the root director of this repo on your machine and enter

```bash
docker-composer up
```

Open another separate terminal and enter the following commands

```bash
docker exec -it fido2-app /bin/bash
cd app
composer install
```

After the installation of the packages dependencies has been completed navigate to the following URL

```text
http://localhost:8082/example/
```

Or click here [http://localhost:8082/example/](http://localhost:8082/example/).