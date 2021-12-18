#!/bin/bash
# Prepare or restore an environment to develop and test code.
# !!! Do not use on real world !!!

# Author Projet nebule
# License GNU GPLv3
# Copyright Projet nebule
# www.nebule.org
# Version 020211218

export PUBSPACE=~/code.master.nebule.org
export WORKSPACE=~/workspace/nebule-php
export password_entity=''

cd $PUBSPACE || return 1
cd $PUBSPACE || exit 1

export LIB_RID_SECURITY_AUTHORITY='a4b210d4fb820a5b715509e501e36873eb9e27dca1dd591a98a5fc264fd2238adf4b489d.none.288'
export LIB_RID_CODE_AUTHORITY='2b9dd679451eaca14a50e7a65352f959fc3ad55efc572dcd009c526bc01ab3fe304d8e69.none.288'
export LIB_RID_TIME_AUTHORITY='bab7966fd5b483f9556ac34e4fac9f778d0014149f196236064931378785d81cae5e7a6e.none.288'
export LIB_RID_DIRECTORY_AUTHORITY='0a4c1e7930a65672379616a2637b84542049b416053ac0d9345300189791f7f8e05f3ed4.none.288'
export LIB_RID_CODE_BRANCH='50e1d0348892e7b8a555301983bccdb8a07871843ed8f392d539d3d90f37ea8c2a54d72a.none.288'
export LIB_RID_INTERFACE_BOOTSTRAP='fc9bb365082ea3a3c8e8e9692815553ad9a70632fe12e9b6d54c8ae5e20959ce94fbb64f.none.288'
export LIB_RID_INTERFACE_LIBRARY='780c5e2767e15ad2a92d663cf4fb0841f31fd302ea0fa97a53bfd1038a0f1c130010e15c.none.288'
export LIB_RID_INTERFACE_APPLICATIONS='4046edc20127dfa1d99f645a7a4ca3db42e94feffa151319c406269bd6ede981c32b96e2.none.288'
export NID_CODE_BRANCH='81de9f10eb1479bbb219c166547b6d4eb690672feadf0f3841cacf58dbb21f537252b011.none.288'

# Prepare all links specifically for develop and tests.
# Same password.
# Just for TESTS !!!
# openssl genrsa -aes256 -out puppetmaster.develop.key.pem 4096
export puppetmaster_develop_key='-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: AES-256-CBC,7CF540C67BD76B080B14E12B344F1FCB

+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
-----END RSA PRIVATE KEY-----'
# openssl genrsa -aes256 -out security.master.develop.key.pem 1024
export security_authority_develop_key='-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: AES-256-CBC,EBC8E5442ECF8451FD9B258BADC245B2

ALziZ+Cmm8DF4UMMsczkEbpPDaZymxi2oLMRlZtNAufO9Ee5un6PX/B3kfZ1bL8w
C3sYKjUPUVuR705+h9heudzhihqjhWvVxI7yqfZJcaS/HVwQpSXFG6xA7puW7nfQ
N9elPdKfYOTwgx/jF2dq6lojH/fObOH5S40nPIT8nZcHkV/x0WX7LJvaq66IKUda
arzOi6U0Wcy6vQfO8dDufAPXG/+f6Ag8CuaL27b2/6zejqZszTEJkzakdIhDj598
d1tuPsV8/1LMoL67lKUO4k5Ogtj6wNqoQOP518BcK4zQEtd5y178FW+2uVdArYBx
TBnElxvlh8W3waSC3vSMqbUjwxZT9APJbhPAN47aAiRuZ4qGYfevR15zP+a3EdZx
dBEoEFZJ3fmH+y/BkN5/KtHgW4HOWZ+HCsCsCqMJgXG5cPvMJFT8VIJcJpTRawhi
ma9DaBFumjAMuRWGnN9ICNbScxbNGGSUzXlnWYhETcZF2GPNf6zMl3TiKHvZm0pQ
Oc9mJz4XRQwyhaErjMDfHMWjnjMRmmrE3Rq+ZpxVBGTKNRtnt+CvTDGjzSD1Ua4R
rYopRrJtEWJ+q2WEiCfs8QAGcYmLYqEPHJipwnpuwOKaowpH2pjpTpNm+fuHb3J4
qmn5lh6xK0aMCOhio7ByUoV1kxBBwooaA/ouxB1Gr9xEHqYMU3l2toLEELvjzZlt
2onfKoSdolQh+UWDXKF2mMC6qgCcYdUBelQGerV+tfeuBkaxe4d0dRAJn9eaYEb1
tr5Ag4w3EtnkD6d88KWUn5HWxkpXAWTw5QqTZXRfhpA=
-----END RSA PRIVATE KEY-----'
export code_authority_develop_key='-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: AES-256-CBC,779550856512A3576B281A421793BF74

CXJaOG/WqyEzqJYxgZtFD3qoSspDt+CWE1QFZZZkZ8BvuMQwMy1hdKoMNYIUfUfC
IzVB0+PHxGRBDWxTGWs8r/DLu7jCDOjiyL9Np1hTCmiLVgCqiCwSe4Fffl548B3P
OUTo6WBBmp/QPGOVd3eaV1YJSpXmwh/OdZfDoiXlo8kw3sDT/v+FE3jYj1QSMfKU
qXRSM3iaEOU4r8onQH5loq86z8arPX1YlYqRLKmMiyVNtQwStGZhWxucN7QXujxc
NC6Zsbiiif4cwK56OTGutU43p/3WJ7YL5vT4spKppj3xH0i6mQjPac95z4R/FVEo
8SUhJ8Gw0FBfanlfOH2dLlzI/TUPKCq9Ho/RTR0mWtC1jfacUUn87x07qxwf/IuZ
Cfo9u3VEU6Wajq/EBrMmCqUO/FZT38uFc0LMp8/U4BcOU090gFEZucjhKpbe1pA8
4Khuq4StHjdckOvi74DS/ZeyaTefdWCL33rHGT8hjOX/yv4THZ8byhs1gksqvb86
DmNcCzRF5wZCCXBcxcWdvrvtLtN0T5el0cnQN4KFJM3sPnsPNVbdLr0vB9rfiUOD
QSd49qspmf8tKWM9KisLamhgVZNmqi1vCJjfkJ3VjF1cOiCA5rtwC5HGF83COBvG
Zt7cC34xj3inBVuwaDmLKTdRdbob5kcv6nqCUfXQ7VRGwaUaiHIoJeqJ3G2i7Tnk
CSREUN8YfpWkomOiCNPlxwq2fjHIBtMew9Hm0UPfW7db/LmLaKK6/FzcWfEWHqWN
YtH4aJOujfNqT3mWOU5jewiyZ710D3Fi13S4XXotBfM=
-----END RSA PRIVATE KEY-----'
export time_authority_develop_key='-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: AES-256-CBC,38028DFEF558CE4E8BABAEBD2FCE0EE3

NcsFvn53agT4RY7yBhCMO9JjhITAEvnNVfiL17IHg4AYwypi8q6iex7ZqiF/o2xB
QdRoHZ+ZgT92sKAa4fr/jSupqC7vuB45gf28vYf7FtqrkNj85Nr/lYff8bzyGVyD
+FjTwYNMhBIeXjHNh5GEKbpfZ/TtYXCdHgdibj5oVJUdEHDdbf+R1aIWoHpI5Rhk
SBq2E7OZTP8YlKfLy4taVUIZRgXBpCrEcS0s8chwyPMrl9bVvnHdP5G/ruz43Hr5
f/nqjlPXvNQJ4gYiGVOZNtCESKMiZdtj2hnDoRX0a4YjvbhheOMGLYmA1ybIsxo8
wrIgKLUczBxNGBljmbQ3viCbU7UOTGkqPrV3FakDVchE6jTChnwqGWnB2+zp5m5P
NUhrcdSK5/dAO7KgfGROErQycERfEGMeRhT7Spx7I+XftruiqyvL5gjlxnNac/+E
4uPfyIfEvXV2e9OSYAc5LvnJfsUTNJokyXClL1ipc10+sv2Wt04FzAsWfPaI1WfI
ffaYbJgP5V6AYflQiWwdIrmgIuSNyMnbHXJwf9z3OMpX3Fs4i9ocKtUXOTRDefYa
Ixi+QoQx5B2qzGbyXdEJt6X0E02/aMoTUTrZWK8UB9Mpm11CRFCVfZ7OHix5KMbg
jQ4qs+HQbMzXB+afmT/wovsPtp8G42sixDE7kU9oqyTqi0vrWFR+YEyjLrf0aBXQ
gZphmKn97ASjR270lYdtCKhACN0AqggCTx20QbJ7zbbhxQ34gu6GALaU5dtXV5k2
qjZyhoqrvMo4uTBscsvz/M3qZUpFx4fhaokU2gv4NBJ1/2I2+FLQlOPXqCA3GWoz
-----END RSA PRIVATE KEY-----'
export directory_authority_develop_key='-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: AES-256-CBC,4AD18E9C81FA13F38339D32792F57317

Kg4l1JEU3UFMJuCIR3wLRsOwAn1WXEc6DtdEOQersWcB10Rqf+VGnSHfe7u+tvRE
q1K08NfzB0zIeAns3WxqDnLy6qlRf17zE02qGIVujRWlC2iQsu/bb7g1KCPLJ1Gb
C+4duCg5IQLPMiXh1G5MXS7QHrg2xyMyZcKd7A8sU2YdImBvT0MtuXV84ZjDDQX0
coYlgsGyUQXDguynS2PiyO/IGy3kQPnJ1joc6+jTxxpHT4vn5gG/b25XTl02YXvP
9WEDE8KahZQMcD0FRawThYJMpEZR2npwckK6NNeN9wedcJFRe/I/6lipzeUcg2BL
jDC8z9bEhw5WrpY/OwfKLZtglrbzkDhgzGQwu+FEFHu/II1/2E666ySnLCal1T98
KOYI9NuzKDInMC2fxplPMsXK44H+2x0M7mryFp+q8PLJ2M5PI1rEQ008x9uVHE7W
kU4TRz1Z9X4LwiKWxoQMa1uUOUAznoBo1O+z0aa1I/XSGZGKlhfqSLEk3ib+C2gA
OMrj4HouJr40igurScruyQblP0Er6GWZM1Jt78q5N9fpRRq0D7jbmR/hX7Wh9wRC
twzPaEU7ZSR8RZ83j+IaQeryV/I9Xzy2+UtFT1bIQZfDGLyHHzWr2H5Thhg9L13c
wVOhNgBLg1OaQCS35qj7cVtcnfWHLrW/xUIS4Deyb7qCja7kLNbfj0NnRBpieeCl
LaFSte6xc8Wc9GcajaAVhvwGBHxkgpFpyfVTnBZJipClINqmliFldwlr9Q8UMqKi
4az53E8XuQluQztRYdNUdHr63/3zW64d+yKDhsJHnwJUcDi2QWiXWlacBA/7NBI1
-----END RSA PRIVATE KEY-----'

function work_full_reinit()
{
  echo ' > work reinit full'

  echo ' > prep'
  sudo rm -rf l o
  sudo mkdir -p l o
  [ -f e ] && sudo rm -f e
  [ -f c ] && sudo rm -f c

  sudo chown 1000.33 l o
  sudo chmod 775 l o

  cat "${WORKSPACE}/nebule.env" \
    | sed 's/^puppetmaster = .*/puppetmaster = '"${puppetmaster_develop_pem_hash}"'/' \
    | sed 's/^codeBranch = .*/codeBranch = develop/' \
    > c

  echo ' > obj'
  echo -n "${puppetmaster_develop_key}" > "o/${puppetmaster_develop_key_hash}"
  echo -n "${security_authority_develop_key}" > "o/${security_authority_develop_key_hash}"
  echo -n "${code_authority_develop_key}" > "o/${code_authority_develop_key_hash}"
  echo -n "${time_authority_develop_key}" > "o/${time_authority_develop_key_hash}"
  echo -n "${directory_authority_develop_key}" > "o/${directory_authority_develop_key_hash}"
  echo -n "${puppetmaster_develop_pem}" > "o/${puppetmaster_develop_pem_hash}"
  echo -n "${security_authority_develop_pem}" > "o/${security_authority_develop_pem_hash}"
  echo -n "${code_authority_develop_pem}" > "o/${code_authority_develop_pem_hash}"
  echo -n "${time_authority_develop_pem}" > "o/${time_authority_develop_pem_hash}"
  echo -n "${directory_authority_develop_pem}" > "o/${directory_authority_develop_pem_hash}"

  pemOID=$(echo -n 'application/x-pem-file' | sha256sum | cut -d' ' -f1)'.sha2.256'
  typeRID=$(echo -n 'nebule/objet/type' | sha256sum | cut -d' ' -f1)'.sha2.256'
  nameRID=$(echo -n 'nebule/objet/nom' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localRID=$(echo -n 'nebule/objet/entite/localisation' | sha256sum | cut -d' ' -f1)'.sha2.256'
  lauthOID=$(echo -n 'nebule/objet/entite/autorite/locale' | sha256sum | cut -d' ' -f1)'.sha2.256'

  echo ' > links puppetmaster'
  nameOID=$(echo -n 'puppetmaster' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://puppetmaster.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  links=(
    "nebule:link/2:0_0>020210714/l>${puppetmaster_develop_pem_hash}>${pemOID}>${typeRID}"
    #"nebule:link/2:0_0>020210714/l>${puppetmaster_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>020210714/l>${puppetmaster_develop_pem_hash}>${nameOID}>${nameRID}"
    "nebule:link/2:0_0>020210714/l>${puppetmaster_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>020210714/l>${security_authority_develop_pem_hash}>${LIB_RID_SECURITY_AUTHORITY}"
    "nebule:link/2:0_0>020210714/l>${security_authority_develop_pem_hash}>${lauthOID}"
    "nebule:link/2:0_0>020210714/l>${code_authority_develop_pem_hash}>${LIB_RID_CODE_AUTHORITY}"
    "nebule:link/2:0_0>020210714/l>${directory_authority_develop_pem_hash}>${LIB_RID_DIRECTORY_AUTHORITY}"
    "nebule:link/2:0_0>020210714/l>${time_authority_develop_pem_hash}>${LIB_RID_TIME_AUTHORITY}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${puppetmaster_develop_key_hash}" "${puppetmaster_develop_pem_hash}" 512
  done

  echo ' > links security authority'
  nameOID=$(echo -n 'cerberus' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://cerberus.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  links=(
    "nebule:link/2:0_0>020210714/l>${security_authority_develop_pem_hash}>${pemOID}>${typeRID}"
    #"nebule:link/2:0_0>020210714/l>${security_authority_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>020210714/l>${security_authority_develop_pem_hash}>${nameOID}>${nameRID}"
    "nebule:link/2:0_0>020210714/l>${security_authority_develop_pem_hash}>${localOID}>${localRID}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${security_authority_develop_key_hash}" "${security_authority_develop_pem_hash}" 256
  done

  echo ' > links code authority'
  nameOID=$(echo -n 'bachue' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://bachue.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  links=(
    "nebule:link/2:0_0>020210714/l>${code_authority_develop_pem_hash}>${pemOID}>${typeRID}"
    #"nebule:link/2:0_0>020210714/l>${code_authority_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>020210714/l>${code_authority_develop_pem_hash}>${nameOID}>${nameRID}"
    "nebule:link/2:0_0>020210714/l>${code_authority_develop_pem_hash}>${localOID}>${localRID}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256
  done

  echo ' > links time authority'
  nameOID=$(echo -n 'kronos' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://kronos.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  links=(
    "nebule:link/2:0_0>020210714/l>${time_authority_develop_pem_hash}>${pemOID}>${typeRID}"
    #"nebule:link/2:0_0>020210714/l>${time_authority_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>020210714/l>${time_authority_develop_pem_hash}>${nameOID}>${nameRID}"
    "nebule:link/2:0_0>020210714/l>${time_authority_develop_pem_hash}>${localOID}>${localRID}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${time_authority_develop_key_hash}" "${time_authority_develop_pem_hash}" 256
  done

  echo ' > links directory authority'
  nameOID=$(echo -n 'asabiyya' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://asabiyya.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  links=(
    "nebule:link/2:0_0>020210714/l>${directory_authority_develop_pem_hash}>${pemOID}>${typeRID}"
    #"nebule:link/2:0_0>020210714/l>${directory_authority_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>020210714/l>${directory_authority_develop_pem_hash}>${nameOID}>${nameRID}"
    "nebule:link/2:0_0>020210714/l>${directory_authority_develop_pem_hash}>${localOID}>${localRID}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${directory_authority_develop_key_hash}" "${directory_authority_develop_pem_hash}" 256
  done

  sudo chown 1000.33 l/*
  sudo chmod 644 l/*
  sudo chown 1000.33 o/*
  sudo chmod 644 o/*
}

function work_export()
{
  echo ' > work export codes'
  echo ' ! do nothing by now!'
}

function work_dev_deploy()
{
  echo ' > work dev deploy codes'
  echo ' > copy code on test environment'
  cp "${WORKSPACE}/bootstrap.php" "${PUBSPACE}/index.php"

  echo " > RID code branch : ${LIB_RID_CODE_BRANCH}"
  echo " > NID code branch : ${NID_CODE_BRANCH}"

  nameOID=$(echo -n 'develop' | sha256sum | cut -d' ' -f1)'.sha2.256'
  nameRID=$(echo -n 'nebule/objet/nom' | sha256sum | cut -d' ' -f1)'.sha2.256'

  echo ' > links'
  echo '   - code branch'
  link="nebule:link/2:0_0>${current_date}/l>${LIB_RID_CODE_BRANCH}>${NID_CODE_BRANCH}>${LIB_RID_CODE_BRANCH}"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256
  echo '   - name'
  link="nebule:link/2:0_0>${current_date}/l>${NID_CODE_BRANCH}>${nameOID}>${nameRID}"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256
}

function work_refresh()
{
  echo ' > work refresh codes'
  current_date=$(date "+0%Y%m%d%H%M%S")
  echo " > date : ${current_date}"

  phpOID=$(echo -n 'application/x-httpd-php' | sha256sum | cut -d' ' -f1)'.sha2.256'
  typeRID=$(echo -n 'nebule/objet/type' | sha256sum | cut -d' ' -f1)'.sha2.256'

  bootstrap_hash=$(sha256sum "${WORKSPACE}/bootstrap.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new bootstrap : ${bootstrap_hash}"
  cp "${WORKSPACE}/bootstrap.php" "o/${bootstrap_hash}"

  cat > "${WORKSPACE}/lib_nebule.php" << EOF
<?php
declare(strict_types=1);
namespace Nebule\Library;
EOF
  for F in "${WORKSPACE}/lib_nebule"/*
  do
    library_hash=$(sha256sum "${F}" | cut -d' ' -f1)'.sha2.256'
    echo -e "\n\n" >> "${WORKSPACE}/lib_nebule.php"
    echo '// ========================================================================================' >> "${WORKSPACE}/lib_nebule.php"
    echo '//   '$(basename "${F}") >> "${WORKSPACE}/lib_nebule.php"
    echo '//   0'$(stat -c '%y' "${F}" | cut -d' ' -f1-2 | cut -d. -f1 | tr -dc "[0-9]") >> "${WORKSPACE}/lib_nebule.php"
    echo "//   ${library_hash}" >> "${WORKSPACE}/lib_nebule.php"
    tail +4 "${F}" | grep -v "^use Nebule\Library" >> "${WORKSPACE}/lib_nebule.php"
  done
  library_hash=$(sha256sum "${WORKSPACE}/lib_nebule.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new library : ${library_hash}"
  mv "${WORKSPACE}/lib_nebule.php" "o/${library_hash}"

  sylabe_hash=$(sha256sum "${WORKSPACE}/sylabe.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new sylabe : ${sylabe_hash}"
  cp "${WORKSPACE}/sylabe.php" "o/${sylabe_hash}"

  echo ' > links'
  echo '   - type mime = application/x-httpd-php'
  link="nebule:link/2:0_0>${current_date}/l>${bootstrap_hash}>${phpOID}>${typeRID}"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256
  link="nebule:link/2:0_0>${current_date}/l>${library_hash}>${phpOID}>${typeRID}"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256
  link="nebule:link/2:0_0>${current_date}/l>${sylabe_hash}>${phpOID}>${typeRID}"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256

  echo '   - nebule/objet/interface/web/php/bootstrap in develop branch'
  link="nebule:link/2:0_0>${current_date}/f>${LIB_RID_INTERFACE_BOOTSTRAP}>${bootstrap_hash}>${NID_CODE_BRANCH}"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256
  link="nebule:link/2:0_0>${current_date}/f>${LIB_RID_INTERFACE_LIBRARY}>${library_hash}>${NID_CODE_BRANCH}"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256
  link="nebule:link/2:0_0>${current_date}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${sylabe_hash}>${NID_CODE_BRANCH}"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" "${code_authority_develop_pem_hash}" 256

  sudo chown 1000.33 l/*
  sudo chmod 664 l/*
  sudo chown 1000.33 o/*
  sudo chmod 664 o/*
}

function sign_write_link()
{
  link="${1}"
  key="${2}"
  eid="${3}"
  size="${4}"

  logger "sign_write_link ${link} with ${eid}"
  slink=$(echo -n "${link}" | openssl dgst -hex -"sha${size}" -sign "o/${key}" -passin "pass:${password_entity}" | cut -d ' ' -f2)
  flink="${link}_${eid}>${slink}.sha2.${size}"
  nid1=$(echo "${link}" | cut -d_ -f2 | cut -d/ -f2 | cut -d '>' -f2)
  nid2=$(echo "${link}" | cut -d_ -f2 | cut -d/ -f2 | cut -d '>' -f3)
  nid3=$(echo "${link}" | cut -d_ -f2 | cut -d/ -f2 | cut -d '>' -f4)
  nid4=$(echo "${link}" | cut -d_ -f2 | cut -d/ -f2 | cut -d '>' -f5)
  touch "l/${nid1}"
  touch "l/${nid2}"
  touch "l/${nid3}"
  touch "l/${nid4}"
  [ "${nid1}" != '' ] && [ "$(grep '${flink}' l/${nid1})" == '' ] && echo "${flink}" >> "l/${nid1}"
  [ "${nid2}" != '' ] && [ "$(grep '${flink}' l/${nid2})" == '' ] && echo "${flink}" >> "l/${nid2}"
  [ "${nid3}" != '' ] && [ "$(grep '${flink}' l/${nid3})" == '' ] && echo "${flink}" >> "l/${nid3}"
  [ "${nid4}" != '' ] && [ "$(grep '${flink}' l/${nid4})" == '' ] && echo "${flink}" >> "l/${nid4}"
  echo "${flink}" >> "l/h"
}

# Recherche ou demande le mot de passe de l'entité.
if [ -f ~/priv/default.password ]
then
  password_entity=$(cat ~/priv/default.password)
else
  read -r -s -p ' ? password : ' password_entity
fi
echo ''

# Extrait les clés publiques.
export puppetmaster_develop_pem=$(       echo -n "${puppetmaster_develop_key}"        | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export security_authority_develop_pem=$( echo -n "${security_authority_develop_key}"  | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export code_authority_develop_pem=$(     echo -n "${code_authority_develop_key}"      | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export time_authority_develop_pem=$(     echo -n "${time_authority_develop_key}"      | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export directory_authority_develop_pem=$(echo -n "${directory_authority_develop_key}" | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")

export puppetmaster_develop_key_hash=$(       echo -n "$puppetmaster_develop_key"        | sha256sum | cut -d' ' -f1)'.sha2.256'
export puppetmaster_develop_pem_hash=$(       echo -n "$puppetmaster_develop_pem"        | sha256sum | cut -d' ' -f1)'.sha2.256'
export security_authority_develop_key_hash=$( echo -n "$security_authority_develop_key"  | sha256sum | cut -d' ' -f1)'.sha2.256'
export security_authority_develop_pem_hash=$( echo -n "$security_authority_develop_pem"  | sha256sum | cut -d' ' -f1)'.sha2.256'
export code_authority_develop_key_hash=$(     echo -n "$code_authority_develop_key"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export code_authority_develop_pem_hash=$(     echo -n "$code_authority_develop_pem"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export time_authority_develop_key_hash=$(     echo -n "$time_authority_develop_key"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export time_authority_develop_pem_hash=$(     echo -n "$time_authority_develop_pem"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export directory_authority_develop_key_hash=$(echo -n "$directory_authority_develop_key" | sha256sum | cut -d' ' -f1)'.sha2.256'
export directory_authority_develop_pem_hash=$(echo -n "$directory_authority_develop_pem" | sha256sum | cut -d' ' -f1)'.sha2.256'

function mode_loop
{
  echo ' > mode loop'
  loop_type='r'
  while true
  do
  
  echo ' = wait'
  read -r -n 1 -p '[r] refresh codes / [d] dev deploy codes  / [e] export codes / [f] reinit full / [q] quit : ' loop_type
  echo -e "\n"
  
  cd $PUBSPACE || return 1
  cd $PUBSPACE || exit 1
  
  case "${loop_type}" in
    f) work_full_reinit; work_dev_deploy; work_refresh;;
    d) work_dev_deploy; work_refresh;;
    e) work_export;;
    q) echo ' > quit'; break;;
    *) work_dev_deploy;;
  esac
  
  done
  echo ' > end'
}

function mode_once
{
  echo ' > mode once'
  case "${1}" in
    f) work_full_reinit; work_dev_deploy; work_refresh;;
    d) work_dev_deploy; work_refresh;;
    e) work_export;;
    r) work_refresh;;
  esac
  echo ' > end'
}

function main
{
  echo ' > start'

  echo "   - puppetmaster        : ${puppetmaster_develop_pem_hash}"
  echo "     - key               : ${puppetmaster_develop_key_hash}"
  echo "   - security authority  : ${security_authority_develop_pem_hash}"
  echo "     - key               : ${security_authority_develop_key_hash}"
  echo "   - code authority      : ${code_authority_develop_pem_hash}"
  echo "     - key               : ${code_authority_develop_key_hash}"
  echo "   - time authority      : ${time_authority_develop_pem_hash}"
  echo "     - key               : ${time_authority_develop_key_hash}"
  echo "   - directory authority : ${directory_authority_develop_pem_hash}"
  echo "     - key               : ${directory_authority_develop_key_hash}"

  if [ "${1}" == '' ]
  then
    mode_loop
  else
    mode_once "${1}"
  fi
}

main "${1}"
