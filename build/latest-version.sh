#!/usr/bin/env bash
set -eu
set -o pipefail

USAGE='latest-version.sh <<docker registry>> [-h|--help]'

DOC=<<-"doc"
  This script determines the docker image with the highest semantic version number
  given a repository.

  Note that simply using 'latest' won't work for lots of reasons which are
  explained in more detail here:
  http://container-solutions.com/docker-latest-confusion/

  The first parameter is the docker registry (e.g. mysql/mysql-cluster) it should
  always be quoted to prevent bash thinking that its a path.
doc

# check that wget is installed
if ! which 'wget' > /dev/null ; then
  echo 'wget cannot be found on path' >&2
  exit 1
fi

# check that jq is installed
if ! which 'jq' > /dev/null ; then
  echo 'wget cannot be found on path' >&2
  exit 1
fi

# check that tr is installed
if ! which 'tr' > /dev/null ; then
  echo 'wget cannot be found on path' >&2
  exit 1
fi

# check that we have a GNU version of getopt
set +e
getopt -T
if [[ "${?}" -ne 4 ]]; then
  printf '%s\n' "First getopt on path is not GNU getopt." >&2
  exit 1
fi
set -e

ARGUMENTS="$(getopt -o h --long help -n "${0:-}" -- "${@:-}")"

eval set -- "${ARGUMENTS}"

while true; do
  case "${1:-}" in
  -h | --help)
    printf '%s\n' "${USAGE}" >&2
    exit 201
    shift
    ;;
  --)
    shift
    break
    ;;
  *)
    echo 'Unknown option' >&2
    exit 3
    ;;
  esac
done

REGISTRY=${1:-}
if [[ "${REGISTRY}" =~ \(\[.-_\]\|\[a-zA-Z0-9\]\)+?\(/\(\[._-\]\|\[a-zA-Z0-9\]\)+\)? ]]; then
  echo "${REGISTRY} is not a valid docker registry" >&2
  exit 2
fi

#Grab a list of all the available versions from the docker hub - stick the results in an array
if ! VERSION_TEXT="$(wget -q "https://registry.hub.docker.com/v1/repositories/${REGISTRY}/tags" -O -  | jq '.[] | .name' | tr -d '"' )"; then
  echo 'There was a problem getting the image details from the docker hub, is this a real docker registry?' >&2
  exit 4
fi
readarray -t VERSIONS <<< "${VERSION_TEXT}"

#Drop all the versions which are not it x.x.x format (i.e. latest, 2.1.4-rc etc.)
for INDEX in "${!VERSIONS[@]}" ; do
  if [[ ! "${VERSIONS[${INDEX}]}" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    unset -v "VERSIONS[${INDEX}]"
  fi
done
VERSIONS=("${VERSIONS[@]}")
LATEST_VERSION="$(printf '%s\n' "${VERSIONS[@]}" | sort -V | tail -1 )"

echo "${LATEST_VERSION}"
