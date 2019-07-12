FROM mattrayner/lamp:latest-1604

RUN apt update
RUN apt-get install -y php7.3-gmp

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs

CMD ["/run.sh"]