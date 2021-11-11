#!/bin/bash
# Prepare or restore an environment to develop and test code.

# Author Projet nebule
# License GNU GPLv3
# Copyright Projet nebule
# www.nebule.org
# Version 020211111

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
    | sed 's/^puppetmaster = .*/puppetmaster = '"${puppetmaster_develop_key_hash}"'/' \
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

  echo ' > links puppetmaster'
  links=(
    #'nebule:link/2:0_0>020210714/l>'"${puppetmaster_develop_pem_hash}"'>5d5b09f6dcb2d53a5fffc60c4ac0d55fabdf556069d6631545f42aa6e3500f2e.sha2.256>8e2adbda190535721fc8fceead980361e33523e97a9748aba95642f8310eb5ec.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${puppetmaster_develop_pem_hash}"'>970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864.sha2.256>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${puppetmaster_develop_pem_hash}"'>f976f916d794ad6384c9084cef7f2515305c464b2ab541142d952126ca9367e3.sha2.256>940c75a60c14a24e5f8bda796f72bef57ab1f64713a6fefd9a4097be95a9e96a.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${puppetmaster_develop_pem_hash}"'>90f1075d96b6d74e3b69bc96448993f9f3a02f593ad0795d5b02e992bacf5b39.sha2.256>0f183d69e06108ac3791eb4fe5bf38beec824db0a2d9966caffcfef5bc563355.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${puppetmaster_develop_pem_hash}"'>5dda00620755703a67896a2fc2f07ac7464d871af0809015018b0935c468f9d7.sha2.256'
    #'nebule:link/2:0_0>020210714/l>'"${security_authority_develop_pem_hash}"'>daea63066cd8f5d4a9c8c80f3cc51f3c20b7fc74ac170ab2ce1950999b422f17.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${security_authority_develop_pem_hash}"'>9e854553b868627af36369b5d9e1e9d5ae31a398e2bacb0816e98e5fb6e806ef.sha2.256'
    #'nebule:link/2:0_0>020210714/l>'"${code_authority_develop_pem_hash}"'>b6fef678931e0761314983d9a08c19b095b088cf6500891206ca1f8b78c2d008.sha2.256'
    #'nebule:link/2:0_0>020210714/l>'"${directory_authority_develop_pem_hash}"'>83db082578142c900e36765ebc210893d79ed0ab1127d687f3307c0c061802e6.sha2.256'
    #'nebule:link/2:0_0>020210714/l>'"${time_authority_develop_pem_hash}"'>663eb81c89c27739f0f875617bcd45b3a18d4b8eb859b8c6e5dccbf9085a2ef9.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${security_authority_develop_pem_hash}>${LIB_RID_SECURITY_AUTHORITY}"
    'nebule:link/2:0_0>020210714/l>'"${code_authority_develop_pem_hash}>${LIB_RID_CODE_AUTHORITY}"
    'nebule:link/2:0_0>020210714/l>'"${directory_authority_develop_pem_hash}>${LIB_RID_DIRECTORY_AUTHORITY}"
    'nebule:link/2:0_0>020210714/l>'"${time_authority_develop_pem_hash}>${LIB_RID_TIME_AUTHORITY}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${puppetmaster_develop_key_hash}" 512
  done

  echo ' > links security authority'
  links=(
    'nebule:link/2:0_0>020210714/l>'"${security_authority_develop_pem_hash}"'>970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864.sha2.256>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${security_authority_develop_pem_hash}"'>b1f8e41d467deb5a22301b320e60366806d87c0b707c46447b754251a86409d6.sha2.256>940c75a60c14a24e5f8bda796f72bef57ab1f64713a6fefd9a4097be95a9e96a.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${security_authority_develop_pem_hash}"'>04de1f5ec5ee0c5a0dbe46c3767f8790e3bf5a4434f425ac28207883f7e0dce2.sha2.256>0f183d69e06108ac3791eb4fe5bf38beec824db0a2d9966caffcfef5bc563355.sha2.256'
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${security_authority_develop_key_hash}" 256
  done

  echo ' > links code authority'
  links=(
    'nebule:link/2:0_0>020210714/l>'"${code_authority_develop_pem_hash}"'>970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864.sha2.256>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${code_authority_develop_pem_hash}"'>f981a156e714e4e8ef2610aaba68b7b60f1a63093ccb167e458e107e70dbf20b.sha2.256>940c75a60c14a24e5f8bda796f72bef57ab1f64713a6fefd9a4097be95a9e96a.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${code_authority_develop_pem_hash}"'>c62b6ffd2e60d3f47667f290e0ecdd8d13f78efbe2cb3a74d58e7c0f36019c3e.sha2.256>0f183d69e06108ac3791eb4fe5bf38beec824db0a2d9966caffcfef5bc563355.sha2.256'
    'nebule:link/2:0_0>020210714/l>365ded68b8cb4c1fe3bf7cb9268e0c63afa31870f3da0d54347ffc475dec4101be052c8a.none.288>947726dd6318753268f3bfbe5e87ae2afe220db399c26e119c181a59227b0c60.sha2.256>'"${LIB_RID_CODE_BRANCH}"
    'nebule:link/2:0_0>020210714/l>005ff1d21bb38724f2a03155a11119d86308645552ed0bbb837cea9f724d3bc00be7b626.none.288>f379ccb92b9116442dc65bdc35648a85d3786b34779db7f704a901fa07b00cb6.sha2.256>'"${LIB_RID_CODE_BRANCH}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${code_authority_develop_key_hash}" 256
  done

  echo ' > links time authority'
  links=(
    'nebule:link/2:0_0>020210714/l>'"${time_authority_develop_pem_hash}"'>970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864.sha2.256>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${time_authority_develop_pem_hash}"'>05a498efd197c47e0cfbd12c6454277f346f9c8a95bc14088f3b7c2042fc3e74.sha2.256>940c75a60c14a24e5f8bda796f72bef57ab1f64713a6fefd9a4097be95a9e96a.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${time_authority_develop_pem_hash}"'>2cc0028c3147977f4b1b6b8a266e6a985ec00d85e7b4f09742537796bf807a5c.sha2.256>0f183d69e06108ac3791eb4fe5bf38beec824db0a2d9966caffcfef5bc563355.sha2.256'
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${time_authority_develop_key_hash}" 256
  done

  echo ' > links directory authority'
  links=(
    'nebule:link/2:0_0>020210714/l>'"${directory_authority_develop_pem_hash}"'>970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864.sha2.256>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${directory_authority_develop_pem_hash}"'>d9a5c716428ab8035693c6cc524462041465f2be3a964b35d0049f1c0789fbc6.sha2.256>940c75a60c14a24e5f8bda796f72bef57ab1f64713a6fefd9a4097be95a9e96a.sha2.256'
    'nebule:link/2:0_0>020210714/l>'"${directory_authority_develop_pem_hash}"'>2be08e47682b65beb11480e9883b7bf5d091d6074dfdd2ffccef9e0659d15542.sha2.256>0f183d69e06108ac3791eb4fe5bf38beec824db0a2d9966caffcfef5bc563355.sha2.256'
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${directory_authority_develop_key_hash}" 256
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
}

function work_refresh()
{
  echo ' > work refresh codes'
  current_date=$(date "+0%Y%m%d%H%M%S")
  echo " > date : ${current_date}"

  rid_bootstrap=$(echo -n 'nebule/objet/interface/web/php/bootstrap' | sha256sum | cut -d' ' -f1)'.sha.256'
  rid_library=$(echo -n 'nebule/objet/interface/web/php/bootstrap' | sha256sum | cut -d' ' -f1)'.sha.256'
  rid_sylabe=$(echo -n 'nebule/objet/interface/web/php/bootstrap' | sha256sum | cut -d' ' -f1)'.sha.256'
  nid_type_php=$(echo -n 'application/x-httpd-php' | sha256sum | cut -d' ' -f1)'.sha.256'

  bootstrap_hash=$(sha256sum "${WORKSPACE}/bootstrap.php" | cut -d' ' -f1)'.sha.256'
  library_hash=$(sha256sum "${WORKSPACE}/lib_nebule.php" | cut -d' ' -f1)'.sha.256'
  sylabe_hash=$(sha256sum "${WORKSPACE}/sylabe.php" | cut -d' ' -f1)'.sha.256'
  echo " > new bootstrap : ${bootstrap_hash}"
  echo " > new library : ${bootstrap_hash}"
  echo " > new sylabe : ${bootstrap_hash}"
  cp "${WORKSPACE}/bootstrap.php" "l/${bootstrap_hash}"
  cp "${WORKSPACE}/lib_nebule.php" "l/${library_hash}"
  cp "${WORKSPACE}/sylabe.php" "l/${sylabe_hash}"

  echo ' > links'
  echo '   - type mime = application/x-httpd-php'
  link="nebule:link/2:0_0>${current_date}/l>${bootstrap_hash}>${nid_type_php}>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256"
  link="nebule:link/2:0_0>${current_date}/l>${library_hash}>${nid_type_php}>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256"
  link="nebule:link/2:0_0>${current_date}/l>${sylabe_hash}>${nid_type_php}>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" 256

  echo '   - nebule/objet/interface/web/php/bootstrap in develop branch'
  link="nebule:link/2:0_0>${current_date}/f>${rid_bootstrap}>${bootstrap_hash}>365ded68b8cb4c1fe3bf7cb9268e0c63afa31870f3da0d54347ffc475dec4101be052c8a.none.288"
  link="nebule:link/2:0_0>${current_date}/f>${rid_library}>${library_hash}>365ded68b8cb4c1fe3bf7cb9268e0c63afa31870f3da0d54347ffc475dec4101be052c8a.none.288"
  link="nebule:link/2:0_0>${current_date}/f>${rid_sylabe}>${sylabe_hash}>365ded68b8cb4c1fe3bf7cb9268e0c63afa31870f3da0d54347ffc475dec4101be052c8a.none.288"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" 256

  echo '   - nebule/objet/interface/web/php/bootstrap in stable branch'
  link="nebule:link/2:0_0>${current_date}/f>${rid_bootstrap}>${bootstrap_hash}>005ff1d21bb38724f2a03155a11119d86308645552ed0bbb837cea9f724d3bc00be7b626.none.288"
  link="nebule:link/2:0_0>${current_date}/f>${rid_library}>${library_hash}>005ff1d21bb38724f2a03155a11119d86308645552ed0bbb837cea9f724d3bc00be7b626.none.288"
  link="nebule:link/2:0_0>${current_date}/f>${rid_sylabe}>${sylabe_hash}>005ff1d21bb38724f2a03155a11119d86308645552ed0bbb837cea9f724d3bc00be7b626.none.288"
  sign_write_link "${link}" "${code_authority_develop_key_hash}" 256

  sudo chown 1000.33 l/*
  sudo chmod 664 l/*
  sudo chown 1000.33 o/*
  sudo chmod 664 o/*
}

function sign_write_link()
{
  link="${1}"
  eid="${2}"
  size="${3}"

  logger "sign_write_link ${link} with ${eid}"
  slink=$(echo -n "${link}" | openssl dgst -hex -"sha${size}" -sign "o/${eid}" -passin "pass:${password_entity}" | cut -d ' ' -f2)
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

export puppetmaster_develop_key_hash=$(       echo -n "$puppetmaster_develop_key"        | sha256sum | cut -d' ' -f1)'.sha.256'
export puppetmaster_develop_pem_hash=$(       echo -n "$puppetmaster_develop_pem"        | sha256sum | cut -d' ' -f1)'.sha.256'
export security_authority_develop_key_hash=$( echo -n "$security_authority_develop_key"  | sha256sum | cut -d' ' -f1)'.sha.256'
export security_authority_develop_pem_hash=$( echo -n "$security_authority_develop_pem"  | sha256sum | cut -d' ' -f1)'.sha.256'
export code_authority_develop_key_hash=$(     echo -n "$code_authority_develop_key"      | sha256sum | cut -d' ' -f1)'.sha.256'
export code_authority_develop_pem_hash=$(     echo -n "$code_authority_develop_pem"      | sha256sum | cut -d' ' -f1)'.sha.256'
export time_authority_develop_key_hash=$(     echo -n "$time_authority_develop_key"      | sha256sum | cut -d' ' -f1)'.sha.256'
export time_authority_develop_pem_hash=$(     echo -n "$time_authority_develop_pem"      | sha256sum | cut -d' ' -f1)'.sha.256'
export directory_authority_develop_key_hash=$(echo -n "$directory_authority_develop_key" | sha256sum | cut -d' ' -f1)'.sha.256'
export directory_authority_develop_pem_hash=$(echo -n "$directory_authority_develop_pem" | sha256sum | cut -d' ' -f1)'.sha.256'

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
  echo "   - security authority  : ${security_authority_develop_pem_hash}"
  echo "   - code authority      : ${code_authority_develop_pem_hash}"
  echo "   - time authority      : ${time_authority_develop_pem_hash}"
  echo "   - directory authority : ${directory_authority_develop_pem_hash}"

  if [ "${1}" == '' ]
  then
    mode_loop
  else
    mode_once "${1}"
  fi
}

main "${1}"
