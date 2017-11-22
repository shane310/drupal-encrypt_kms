FROM debian:jessie

WORKDIR /data

# Install git, curl and yamllint.
RUN apt-get update && \
    apt-get install --yes --force-yes git curl python-pip && \
    pip install yamllint && \
    rm -rf /var/lib/apt/lists/* ~/.cache

# Latest version of jq.
RUN curl -s -L https://github.com/stedolan/jq/releases/download/jq-1.5/jq-linux64 -o /usr/local/bin/jq && \
    chmod +rx /usr/local/bin/jq
