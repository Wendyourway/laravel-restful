FROM wendyourway/laravel-docker:latest
LABEL maintainer Mark <chumheramis@gmail.com>
# Environment Parameters
ENV ARTISAN_MIGRATE=1
ENV ARTISAN_SEED=1
ENV ARTISAN_SERVE=1
# Expose Ports
EXPOSE 8000
ENTRYPOINT ["start-container"]