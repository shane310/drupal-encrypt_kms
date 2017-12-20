#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

#/ Usage:       ./package-excludes.sh <path>
#/ Description: Ensures that certain files are excluded from the drupal.org package.
#/ Options:
#/   --help: Display this help message
usage() { grep '^#/' "$0" | cut -c4- ; exit 0 ; }
expr "$*" : ".*--help" > /dev/null && usage

readonly LOG_FILE="/tmp/$(basename "$0").log"
info()    { echo "[INFO]    $*" | tee -a "$LOG_FILE" >&2 ; }
warning() { echo "[WARNING] $*" | tee -a "$LOG_FILE" >&2 ; }
error()   { echo "[ERROR]   $*" | tee -a "$LOG_FILE" >&2 ; }
fatal()   { echo "[FATAL]   $*" | tee -a "$LOG_FILE" >&2 ; exit 1 ; }

GIT_DIR=$@
TMP_DIR=/tmp/package-excludes

cleanup() {
  # Remove temporary files
  # Restart services
  echo "cleaning up"
  rm -rf "${TMP_DIR}"
}

if [[ "${BASH_SOURCE[0]}" = "$0" ]]; then
  trap cleanup EXIT

  mkdir -p ${TMP_DIR}
  git --git-dir="${GIT_DIR}" archive --format=tar --output="${TMP_DIR}/encrypt_kms.tar"
fi
