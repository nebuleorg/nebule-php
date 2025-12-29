#!/bin/bash
# Prepare or restore an environment to develop and test code.

# Author Projet nebule
# License GNU GPLv3
# Copyright Projet nebule
# www.nebule.org
# Version 020251229

echo ' > start'

export PUPPETMASTER_SPACE=~/puppetmaster.nebule.org
export SECURITY_MASTER_SPACE=~/security.master.nebule.org
export CODE_MASTER_SPACE=~/code.master.nebule.org
export TIME_MASTER_SPACE=~/time.master.nebule.org
export DIRECTORY_MASTER_SPACE=~/directory.master.nebule.org
export TEST_SPACE=~/test.nebule.org
export ALL_SPACES="${PUPPETMASTER_SPACE} ${SECURITY_MASTER_SPACE} ${CODE_MASTER_SPACE} ${TIME_MASTER_SPACE} ${DIRECTORY_MASTER_SPACE} ${TEST_SPACE}"
export WORKSPACE=~/workspace/nebule-php
export TESTINSTANCE="${WORKSPACE}/test_instance"
export password_entity=3968761168fe7f4b9df6f6964fb23f5db1a9531569cf78c230c4b6877b7cb0ea

cd "${CODE_MASTER_SPACE}" || return 1
cd "${CODE_MASTER_SPACE}" || exit 1

export LIB_RID_SECURITY_AUTHORITY='a4b210d4fb820a5b715509e501e36873eb9e27dca1dd591a98a5fc264fd2238adf4b489d.none.288'
export LIB_RID_CODE_AUTHORITY='2b9dd679451eaca14a50e7a65352f959fc3ad55efc572dcd009c526bc01ab3fe304d8e69.none.288'
export LIB_RID_TIME_AUTHORITY='bab7966fd5b483f9556ac34e4fac9f778d0014149f196236064931378785d81cae5e7a6e.none.288'
export LIB_RID_DIRECTORY_AUTHORITY='0a4c1e7930a65672379616a2637b84542049b416053ac0d9345300189791f7f8e05f3ed4.none.288'
LIB_RID_CODE_BRANCH=$(grep 'const LIB_RID_CODE_BRANCH' ${WORKSPACE}/bootstrap.php | head -1 | cut -d\' -f2)
export LIB_RID_CODE_BRANCH
export LIB_RID_INTERFACE_BOOTSTRAP='fc9bb365082ea3a3c8e8e9692815553ad9a70632fe12e9b6d54c8ae5e20959ce94fbb64f.none.288'
export IID_INTERFACE_BOOTSTRAP='304f4431cd011211e8fbb57081cd8f1609a25a46ab30476e4b3bffb90d47e73832374176.none.288'
export LIB_RID_INTERFACE_LIBRARY='780c5e2767e15ad2a92d663cf4fb0841f31fd302ea0fa97a53bfd1038a0f1c130010e15c.none.288'
export IID_INTERFACE_LIBRARY='21f6396e921e4373a91d70d13895b04a359316fc269a1c0dc9268a71419ecfb41e88d58d.none.288'
export LIB_RID_INTERFACE_APPLICATIONS='4046edc20127dfa1d99f645a7a4ca3db42e94feffa151319c406269bd6ede981c32b96e2.none.288'
export IID_INTERFACE_AUTENT='9020606a70985a00f1cf73e6aed5cfd46399868871bd26d6c0bd7a202e01759c3d91b97e.none.288'
export IID_INTERFACE_ENTITY='206090aec4ba9e2eaa66737d34ced59cfe73b8342fc020efbd321eded7c8b46440e0875a.none.288'
export IID_INTERFACE_SYLABE='c02030d3b77c52b3e18f36ee9035ed2f3ff68f66425f2960f973ea5cd1cc0240a4d28de1.none.288'
export IID_INTERFACE_KLICTY='d0b02052a575f63a4e87ff320df443a8b417be1b99e8e40592f8f98cbd1adc58c221d501.none.288'
export IID_INTERFACE_MESSAE='2060a0d21853a42093f01d2e4809c2a5e9300b4ec31afbaf18af66ec65586d6c78b2823a.none.288'
export IID_INTERFACE_NEBLOG='05c3dd94a9ae4795c888cb9a6995d1e5a23b43816e2e7fb908b6841694784bc3ecda8adf.none.288'
export IID_INTERFACE_QANTION='20a04016698cd3c996fa69e90bbf3e804c582b8946a5d60e9880cdb24b36b5d376208939.none.288'
export IID_INTERFACE_OPTION='555555712c23ff20740c50e6f15e275f695fe95728142c3f8ba2afa3b5a89b3cd0879211.none.288'
export IID_INTERFACE_UPLOAD='6666661d0923f08d50de4d70be7dc3014e73de3325b6c7b16efd1a6f5a12f5957b68336d.none.288'
export LIB_RID_INTERFACE_APPLICATIONS_DIRECT='f202ca455549a1ddd553251f9c1df49ec6541c3412e52ed5f2ce2adfd772d07d0bfc2d28.none.288'
export LIB_RID_INTERFACE_APPLICATIONS_ACTIVE='ae2b0dd506026c59b27ae93ef2d1ead7a2c893d2662d360c3937b699428010538b5c0af9.none.288'
export LIB_RID_INTERFACE_MODULES='fd66cdc1edfa0285d6ce9d8419847e54ec7df2d293921615d13d35a5879e7e311efff4ad.none.288'
export LIB_RID_INTERFACE_MODULES_TRANSLATE='4a45d825cf72fbf331c07cb4bdd6c65ab13e3b6b10405400d82817ed48ff4691e8699a69.none.288'
export LIB_RID_INTERFACE_MODULES_ACTIVE='1e1531707bb7b0be9f4664fe8010729090f592ed4c3f4e6e37c6365f865a192beee3e970.none.288'
export NID_CODE_BRANCH='81de9f10eb1479bbb219c166547b6d4eb690672feadf0f3841cacf58dbb21f537252b011.none.288'
export INIT_DATE='020250111'

# Prepare all links specifically for develop and tests.
echo " > prep authorities"
puppetmaster_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 4096 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export puppetmaster_develop_key
security_authority1_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 2048 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export security_authority1_develop_key
security_authority2_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 2048 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export security_authority2_develop_key
code_authority1_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 2048 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export code_authority1_develop_key
code_authority2_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 2048 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export code_authority2_develop_key
time_authority1_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 1024 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export time_authority1_develop_key
time_authority2_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 1024 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export time_authority2_develop_key
directory_authority1_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 1024 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export directory_authority1_develop_key
directory_authority2_develop_key=$(openssl genrsa -aes256 -passout pass:${password_entity} 1024 2>&1 | grep -A100 'BEGIN ENCRYPTED PRIVATE KEY')
export directory_authority2_develop_key

function work_full_reinit()
{
  echo ' > work reinit full'

  echo ' > prep masters folders'
  for SPACE in $ALL_SPACES
  do
    echo "   - ${SPACE}"
    sudo rm -rf "${SPACE}/l" "${SPACE}/o"
    mkdir -p "${SPACE}/l" "${SPACE}/o"
    [ -f "${SPACE}/e" ] && sudo rm -f "${SPACE}/e"
    [ -f "${SPACE}/c" ] && sudo rm -f "${SPACE}/c"
    [ -f "${SPACE}/index.php" ] && sudo rm -f "${SPACE}/index.php"

    sudo chown 1000:33 "${SPACE}/l" "${SPACE}/o"
    sudo chmod 775 "${SPACE}/l" "${SPACE}/o"
    touch "${SPACE}/l/mark" "${SPACE}/o/mark"
  done

  echo ' > prep masters configuration'
  cat "${WORKSPACE}/nebule.env" > "${PUPPETMASTER_SPACE}/c"
  sed -i 's/^puppetmaster = .*$/puppetmaster = '"${puppetmaster_develop_pem_hash}"'/' "${PUPPETMASTER_SPACE}/c"
  #sed -i 's/^#permitWrite = .*$/permitWrite = false/' "${PUPPETMASTER_SPACE}/c"
  sed -i 's/^#codeBranch = .*$/codeBranch = develop/' "${PUPPETMASTER_SPACE}/c"
  sed -i 's/^#displayUnsecureURL = .*$/displayUnsecureURL = false/' "${PUPPETMASTER_SPACE}/c"
  sed -i 's/^#permitApplication4 = .*$/permitApplication4 = true/' "${PUPPETMASTER_SPACE}/c"
  sed -i 's/^#permitApplication9 = .*$/permitApplication9 = true/' "${PUPPETMASTER_SPACE}/c"
  sed -i 's/^#permitApplicationModules = .*$/permitApplicationModules = true/' "${PUPPETMASTER_SPACE}/c"

  cat "${WORKSPACE}/nebule.env" > "${SECURITY_MASTER_SPACE}/c"
  sed -i 's/^puppetmaster = .*$/puppetmaster = '"${puppetmaster_develop_pem_hash}"'/' "${SECURITY_MASTER_SPACE}/c"
  #sed -i 's/^#permitWrite = .*$/permitWrite = false/' "${SECURITY_MASTER_SPACE}/c"
  sed -i 's/^#codeBranch = .*$/codeBranch = develop/' "${SECURITY_MASTER_SPACE}/c"
  sed -i 's/^#displayUnsecureURL = .*$/displayUnsecureURL = false/' "${SECURITY_MASTER_SPACE}/c"
  sed -i 's/^#permitApplication4 = .*$/permitApplication4 = true/' "${SECURITY_MASTER_SPACE}/c"
  sed -i 's/^#permitApplication9 = .*$/permitApplication9 = true/' "${SECURITY_MASTER_SPACE}/c"
  sed -i 's/^#permitApplicationModules = .*$/permitApplicationModules = true/' "${SECURITY_MASTER_SPACE}/c"

  cat "${WORKSPACE}/nebule.env" > "${CODE_MASTER_SPACE}/c"
  sed -i 's/^puppetmaster = .*$/puppetmaster = '"${puppetmaster_develop_pem_hash}"'/' "${CODE_MASTER_SPACE}/c"
  #sed -i 's/^#permitWrite = .*$/permitWrite = false/' "${CODE_MASTER_SPACE}/c"
  sed -i 's/^#codeBranch = .*$/codeBranch = develop/' "${CODE_MASTER_SPACE}/c"
  sed -i 's/^#displayUnsecureURL = .*$/displayUnsecureURL = false/' "${CODE_MASTER_SPACE}/c"
  sed -i 's/^#permitApplication4 = .*$/permitApplication4 = true/' "${CODE_MASTER_SPACE}/c"
  sed -i 's/^#permitApplication9 = .*$/permitApplication9 = true/' "${CODE_MASTER_SPACE}/c"
  sed -i 's/^#permitApplicationModules = .*$/permitApplicationModules = true/' "${CODE_MASTER_SPACE}/c"
  sed -i 's/^#permitPublicCreateEntity = .*$/permitPublicCreateEntity = true/' "${CODE_MASTER_SPACE}/c" # FIXME temporary for tests

  cat "${WORKSPACE}/nebule.env" > "${TIME_MASTER_SPACE}/c"
  sed -i 's/^puppetmaster = .*$/puppetmaster = '"${puppetmaster_develop_pem_hash}"'/' "${TIME_MASTER_SPACE}/c"
  #sed -i 's/^#permitWrite = .*$/permitWrite = false/' "${TIME_MASTER_SPACE}/c"
  sed -i 's/^#codeBranch = .*$/codeBranch = develop/' "${TIME_MASTER_SPACE}/c"
  sed -i 's/^#displayUnsecureURL = .*$/displayUnsecureURL = false/' "${TIME_MASTER_SPACE}/c"
  sed -i 's/^#permitApplication4 = .*$/permitApplication4 = true/' "${TIME_MASTER_SPACE}/c"
  sed -i 's/^#permitApplication9 = .*$/permitApplication9 = true/' "${TIME_MASTER_SPACE}/c"
  sed -i 's/^#permitApplicationModules = .*$/permitApplicationModules = true/' "${TIME_MASTER_SPACE}/c"

  cat "${WORKSPACE}/nebule.env" > "${DIRECTORY_MASTER_SPACE}/c"
  sed -i 's/^puppetmaster = .*$/puppetmaster = '"${puppetmaster_develop_pem_hash}"'/' "${DIRECTORY_MASTER_SPACE}/c"
  #sed -i 's/^#permitWrite = .*$/permitWrite = false/' "${DIRECTORY_MASTER_SPACE}/c"
  sed -i 's/^#codeBranch = .*$/codeBranch = develop/' "${DIRECTORY_MASTER_SPACE}/c"
  sed -i 's/^#displayUnsecureURL = .*$/displayUnsecureURL = false/' "${DIRECTORY_MASTER_SPACE}/c"
  sed -i 's/^#permitApplication4 = .*$/permitApplication4 = true/' "${DIRECTORY_MASTER_SPACE}/c"
  sed -i 's/^#permitApplication9 = .*$/permitApplication9 = true/' "${DIRECTORY_MASTER_SPACE}/c"
  sed -i 's/^#permitApplicationModules = .*$/permitApplicationModules = true/' "${DIRECTORY_MASTER_SPACE}/c"

  cat "${WORKSPACE}/nebule.env" > "${TEST_SPACE}/c"
  sed -i 's/^puppetmaster = .*$/puppetmaster = '"${puppetmaster_develop_pem_hash}"'/' "${TEST_SPACE}/c"
  sed -i 's/^#hostURL = .*$/hostURL = bachue.developpement.nebule.org/' "${TEST_SPACE}/c"
  sed -i 's/^#permitUploadLink = .*$/permitUploadLink = true/' "${TEST_SPACE}/c"
  sed -i 's/^#codeBranch = .*$/codeBranch = develop/' "${TEST_SPACE}/c"
  sed -i 's/^#logsLevel = .*$/logsLevel = DEVELOP/' "${TEST_SPACE}/c"
  sed -i 's/^#permitPublicCreateEntity = .*$/permitPublicCreateEntity = true/' "${TEST_SPACE}/c"
  sed -i 's/^#permitServerEntityAsAuthority = .*$/permitServerEntityAsAuthority = true/' "${TEST_SPACE}/c"
  sed -i 's/^#permitDefaultEntityAsAuthority = .*$/permitDefaultEntityAsAuthority = true/' "${TEST_SPACE}/c"
  sed -i 's/^#displayUnsecureURL = .*$/displayUnsecureURL = false/' "${TEST_SPACE}/c"
  sed -i 's/^#permitApplication4 = .*$/permitApplication4 = true/' "${TEST_SPACE}/c"
  sed -i 's/^#permitApplication9 = .*$/permitApplication9 = true/' "${TEST_SPACE}/c"
  sed -i 's/^#permitLogsOnDebugFile = .*$/permitLogsOnDebugFile = true/' "${TEST_SPACE}/c"
  sed -i 's/^#permitApplicationModules = .*$/permitApplicationModules = true/' "${TEST_SPACE}/c"
  sed -i 's/^#permitApplicationModulesExternal = .*$/permitApplicationModulesExternal = true/' "${TEST_SPACE}/c"
  sed -i 's/^#permitApplicationModulesTranslate = .*$/permitApplicationModulesTranslate = true/' "${TEST_SPACE}/c"

  echo ' > prep masters objects'
  echo '   - obj puppetmaster'
  echo -n "${puppetmaster_develop_key}" > "${PUPPETMASTER_SPACE}/o/${puppetmaster_develop_key_hash}"
  echo -n "${security_authority1_develop_key}" > "${PUPPETMASTER_SPACE}/o/${security_authority1_develop_key_hash}"
  echo -n "${security_authority2_develop_key}" > "${PUPPETMASTER_SPACE}/o/${security_authority2_develop_key_hash}"
  echo -n "${code_authority1_develop_key}" > "${PUPPETMASTER_SPACE}/o/${code_authority1_develop_key_hash}"
  echo -n "${code_authority2_develop_key}" > "${PUPPETMASTER_SPACE}/o/${code_authority2_develop_key_hash}"
  echo -n "${time_authority1_develop_key}" > "${PUPPETMASTER_SPACE}/o/${time_authority1_develop_key_hash}"
  echo -n "${time_authority2_develop_key}" > "${PUPPETMASTER_SPACE}/o/${time_authority2_develop_key_hash}"
  echo -n "${directory_authority1_develop_key}" > "${PUPPETMASTER_SPACE}/o/${directory_authority1_develop_key_hash}"
  echo -n "${directory_authority2_develop_key}" > "${PUPPETMASTER_SPACE}/o/${directory_authority2_develop_key_hash}"
  echo -n "${puppetmaster_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${puppetmaster_develop_pem_hash}"
  echo -n "${security_authority1_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${security_authority1_develop_pem_hash}"
  echo -n "${security_authority2_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${security_authority2_develop_pem_hash}"
  echo -n "${code_authority1_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${code_authority1_develop_pem_hash}"
  echo -n "${code_authority2_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${code_authority2_develop_pem_hash}"
  echo -n "${time_authority1_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${time_authority1_develop_pem_hash}"
  echo -n "${time_authority2_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${time_authority2_develop_pem_hash}"
  echo -n "${directory_authority1_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${directory_authority1_develop_pem_hash}"
  echo -n "${directory_authority2_develop_pem}" > "${PUPPETMASTER_SPACE}/o/${directory_authority2_develop_pem_hash}"

  echo '   - obj security master'
  echo -n "${puppetmaster_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${puppetmaster_develop_pem_hash}"
  echo -n "${security_authority1_develop_key}" > "${SECURITY_MASTER_SPACE}/o/${security_authority1_develop_key_hash}"
  echo -n "${security_authority2_develop_key}" > "${SECURITY_MASTER_SPACE}/o/${security_authority2_develop_key_hash}"
  echo -n "${security_authority1_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${security_authority1_develop_pem_hash}"
  echo -n "${security_authority2_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${security_authority2_develop_pem_hash}"
  echo -n "${code_authority1_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${code_authority1_develop_pem_hash}"
  echo -n "${code_authority2_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${code_authority2_develop_pem_hash}"
  echo -n "${time_authority1_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${time_authority1_develop_pem_hash}"
  echo -n "${time_authority2_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${time_authority2_develop_pem_hash}"
  echo -n "${directory_authority1_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${directory_authority1_develop_pem_hash}"
  echo -n "${directory_authority2_develop_pem}" > "${SECURITY_MASTER_SPACE}/o/${directory_authority2_develop_pem_hash}"

  echo '   - obj code master'
  echo -n "${puppetmaster_develop_pem}" > "${CODE_MASTER_SPACE}/o/${puppetmaster_develop_pem_hash}"
  echo -n "${puppetmaster_develop_key}" > "${CODE_MASTER_SPACE}/o/${puppetmaster_develop_key_hash}"
  echo -n "${code_authority1_develop_key}" > "${CODE_MASTER_SPACE}/o/${code_authority1_develop_key_hash}"
  echo -n "${code_authority2_develop_key}" > "${CODE_MASTER_SPACE}/o/${code_authority2_develop_key_hash}"
  echo -n "${security_authority1_develop_pem}" > "${CODE_MASTER_SPACE}/o/${security_authority1_develop_pem_hash}"
  echo -n "${security_authority2_develop_pem}" > "${CODE_MASTER_SPACE}/o/${security_authority2_develop_pem_hash}"
  echo -n "${code_authority1_develop_pem}" > "${CODE_MASTER_SPACE}/o/${code_authority1_develop_pem_hash}"
  echo -n "${code_authority2_develop_pem}" > "${CODE_MASTER_SPACE}/o/${code_authority2_develop_pem_hash}"
  echo -n "${time_authority1_develop_pem}" > "${CODE_MASTER_SPACE}/o/${time_authority1_develop_pem_hash}"
  echo -n "${time_authority2_develop_pem}" > "${CODE_MASTER_SPACE}/o/${time_authority2_develop_pem_hash}"
  echo -n "${directory_authority1_develop_pem}" > "${CODE_MASTER_SPACE}/o/${directory_authority1_develop_pem_hash}"
  echo -n "${directory_authority2_develop_pem}" > "${CODE_MASTER_SPACE}/o/${directory_authority2_develop_pem_hash}"

  echo '   - obj time master'
  echo -n "${puppetmaster_develop_pem}" > "${TIME_MASTER_SPACE}/o/${puppetmaster_develop_pem_hash}"
  echo -n "${time_authority1_develop_key}" > "${TIME_MASTER_SPACE}/o/${time_authority1_develop_key_hash}"
  echo -n "${time_authority2_develop_key}" > "${TIME_MASTER_SPACE}/o/${time_authority2_develop_key_hash}"
  echo -n "${security_authority1_develop_pem}" > "${TIME_MASTER_SPACE}/o/${security_authority1_develop_pem_hash}"
  echo -n "${security_authority2_develop_pem}" > "${TIME_MASTER_SPACE}/o/${security_authority2_develop_pem_hash}"
  echo -n "${code_authority1_develop_pem}" > "${TIME_MASTER_SPACE}/o/${code_authority1_develop_pem_hash}"
  echo -n "${code_authority2_develop_pem}" > "${TIME_MASTER_SPACE}/o/${code_authority2_develop_pem_hash}"
  echo -n "${time_authority1_develop_pem}" > "${TIME_MASTER_SPACE}/o/${time_authority1_develop_pem_hash}"
  echo -n "${time_authority2_develop_pem}" > "${TIME_MASTER_SPACE}/o/${time_authority2_develop_pem_hash}"
  echo -n "${directory_authority1_develop_pem}" > "${TIME_MASTER_SPACE}/o/${directory_authority1_develop_pem_hash}"
  echo -n "${directory_authority2_develop_pem}" > "${TIME_MASTER_SPACE}/o/${directory_authority2_develop_pem_hash}"

  echo '   - obj directory master'
  echo -n "${puppetmaster_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${puppetmaster_develop_pem_hash}"
  echo -n "${directory_authority1_develop_key}" > "${DIRECTORY_MASTER_SPACE}/o/${directory_authority1_develop_key_hash}"
  echo -n "${directory_authority2_develop_key}" > "${DIRECTORY_MASTER_SPACE}/o/${directory_authority2_develop_key_hash}"
  echo -n "${security_authority1_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${security_authority1_develop_pem_hash}"
  echo -n "${security_authority2_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${security_authority2_develop_pem_hash}"
  echo -n "${code_authority1_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${code_authority1_develop_pem_hash}"
  echo -n "${code_authority2_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${code_authority2_develop_pem_hash}"
  echo -n "${time_authority1_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${time_authority1_develop_pem_hash}"
  echo -n "${time_authority2_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${time_authority2_develop_pem_hash}"
  echo -n "${directory_authority1_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${directory_authority1_develop_pem_hash}"
  echo -n "${directory_authority2_develop_pem}" > "${DIRECTORY_MASTER_SPACE}/o/${directory_authority2_develop_pem_hash}"

  pemOID=$(echo -n 'application/x-pem-file' | sha256sum | cut -d' ' -f1)'.sha2.256'
  typeRID=$(echo -n 'nebule/objet/type' | sha256sum | cut -d' ' -f1)'.sha2.256'
  nameRID=$(echo -n 'nebule/objet/nom' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localRID=$(echo -n 'nebule/objet/entite/localisation' | sha256sum | cut -d' ' -f1)'.sha2.256'
  lauthOID=$(echo -n 'nebule/objet/entite/autorite/locale' | sha256sum | cut -d' ' -f1)'.sha2.256'
  Pkey=$(echo -n 'nebule/objet/entite/prive' | sha256sum | cut -d' ' -f1)'.sha2.256'
  algoKey=$(echo -n 'nebule/objet/entite/algorithme' | sha256sum | cut -d' ' -f1)'.sha2.256'
  rsa1024=$(echo -n 'rsa.1024' | sha256sum | cut -d' ' -f1)'.sha2.256'
  rsa2048=$(echo -n 'rsa.2048' | sha256sum | cut -d' ' -f1)'.sha2.256'
  rsa4096=$(echo -n 'rsa.4096' | sha256sum | cut -d' ' -f1)'.sha2.256'

  echo ' > links puppetmaster'
  entityNameOID=$(echo -n 'puppetmaster' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://puppetmaster.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  echo -n 'puppetmaster' > "${CODE_MASTER_SPACE}/o/${entityNameOID}"
  echo -n 'http://puppetmaster.nebule.org' > "${CODE_MASTER_SPACE}/o/${localOID}"
  links=(
    "nebule:link/2:0_0>${INIT_DATE}/l>${puppetmaster_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${puppetmaster_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${puppetmaster_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${puppetmaster_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${puppetmaster_develop_pem_hash}>${puppetmaster_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${puppetmaster_develop_pem_hash}>${rsa4096}>${algoKey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${LIB_RID_SECURITY_AUTHORITY}>${security_authority1_develop_pem_hash}>${LIB_RID_SECURITY_AUTHORITY}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${LIB_RID_SECURITY_AUTHORITY}>${security_authority2_develop_pem_hash}>${LIB_RID_SECURITY_AUTHORITY}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${lauthOID}>${security_authority1_develop_pem_hash}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${lauthOID}>${security_authority2_develop_pem_hash}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${LIB_RID_CODE_AUTHORITY}>${code_authority1_develop_pem_hash}>${LIB_RID_CODE_AUTHORITY}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${LIB_RID_CODE_AUTHORITY}>${code_authority2_develop_pem_hash}>${LIB_RID_CODE_AUTHORITY}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${LIB_RID_DIRECTORY_AUTHORITY}>${directory_authority1_develop_pem_hash}>${LIB_RID_DIRECTORY_AUTHORITY}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${LIB_RID_DIRECTORY_AUTHORITY}>${directory_authority2_develop_pem_hash}>${LIB_RID_DIRECTORY_AUTHORITY}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${LIB_RID_TIME_AUTHORITY}>${time_authority1_develop_pem_hash}>${LIB_RID_TIME_AUTHORITY}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${LIB_RID_TIME_AUTHORITY}>${time_authority2_develop_pem_hash}>${LIB_RID_TIME_AUTHORITY}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${puppetmaster_develop_key_hash}" "${puppetmaster_develop_pem_hash}" 512 "${PUPPETMASTER_SPACE}"
    sign_write_link "${link}" "${puppetmaster_develop_key_hash}" "${puppetmaster_develop_pem_hash}" 512 "${SECURITY_MASTER_SPACE}"
    sign_write_link "${link}" "${puppetmaster_develop_key_hash}" "${puppetmaster_develop_pem_hash}" 512 "${CODE_MASTER_SPACE}"
    sign_write_link "${link}" "${puppetmaster_develop_key_hash}" "${puppetmaster_develop_pem_hash}" 512 "${TIME_MASTER_SPACE}"
    sign_write_link "${link}" "${puppetmaster_develop_key_hash}" "${puppetmaster_develop_pem_hash}" 512 "${DIRECTORY_MASTER_SPACE}"
    sign_write_link "${link}" "${puppetmaster_develop_key_hash}" "${puppetmaster_develop_pem_hash}" 512 "${TEST_SPACE}"
  done

  echo ' > links security authority'
  entityNameOID=$(echo -n 'cerberus' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://cerberus.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  echo -n 'cerberus' > "${CODE_MASTER_SPACE}/o/${entityNameOID}"
  echo -n 'http://cerberus.nebule.org' > "${CODE_MASTER_SPACE}/o/${localOID}"
  links=(
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority1_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority2_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority1_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority2_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${security_authority1_develop_pem_hash}>${security_authority1_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${security_authority2_develop_pem_hash}>${security_authority2_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority1_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority2_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority1_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority2_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority1_develop_pem_hash}>${rsa2048}>${algoKey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${security_authority2_develop_pem_hash}>${rsa2048}>${algoKey}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${security_authority1_develop_key_hash}" "${security_authority1_develop_pem_hash}" 256 "${SECURITY_MASTER_SPACE}"
    sign_write_link "${link}" "${security_authority2_develop_key_hash}" "${security_authority2_develop_pem_hash}" 256 "${SECURITY_MASTER_SPACE}"
    sign_write_link "${link}" "${security_authority1_develop_key_hash}" "${security_authority1_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
    sign_write_link "${link}" "${security_authority2_develop_key_hash}" "${security_authority2_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
  done

  echo ' > links code authority'
  entityNameOID=$(echo -n 'bachue' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://bachue.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  echo -n 'bachue' > "${CODE_MASTER_SPACE}/o/${entityNameOID}"
  echo -n 'http://bachue.nebule.org' > "${CODE_MASTER_SPACE}/o/${localOID}"
  links=(
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority1_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority2_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority1_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority2_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${code_authority1_develop_pem_hash}>${code_authority1_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${code_authority2_develop_pem_hash}>${code_authority2_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority1_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority2_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority1_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority2_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority1_develop_pem_hash}>${rsa2048}>${algoKey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${code_authority2_develop_pem_hash}>${rsa2048}>${algoKey}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${code_authority1_develop_key_hash}" "${code_authority1_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
    sign_write_link "${link}" "${code_authority2_develop_key_hash}" "${code_authority2_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
  done

  echo ' > links time authority'
  entityNameOID=$(echo -n 'kronos' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://kronos.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  echo -n 'kronos' > "${CODE_MASTER_SPACE}/o/${entityNameOID}"
  echo -n 'http://kronos.nebule.org' > "${CODE_MASTER_SPACE}/o/${localOID}"
  links=(
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority1_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority2_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority1_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority2_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${time_authority1_develop_pem_hash}>${time_authority1_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${time_authority2_develop_pem_hash}>${time_authority2_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority1_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority2_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority1_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority2_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority1_develop_pem_hash}>${rsa1024}>${algoKey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${time_authority2_develop_pem_hash}>${rsa1024}>${algoKey}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${time_authority1_develop_key_hash}" "${time_authority1_develop_pem_hash}" 256 "${TIME_MASTER_SPACE}"
    sign_write_link "${link}" "${time_authority2_develop_key_hash}" "${time_authority2_develop_pem_hash}" 256 "${TIME_MASTER_SPACE}"
    sign_write_link "${link}" "${time_authority1_develop_key_hash}" "${time_authority1_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
    sign_write_link "${link}" "${time_authority2_develop_key_hash}" "${time_authority2_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
  done

  echo ' > links directory authority'
  entityNameOID=$(echo -n 'asabiyya' | sha256sum | cut -d' ' -f1)'.sha2.256'
  localOID=$(echo -n 'http://asabiyya.nebule.org' | sha256sum | cut -d' ' -f1)'.sha2.256'
  echo -n 'asabiyya' > "${CODE_MASTER_SPACE}/o/${entityNameOID}"
  echo -n 'http://asabiyya.nebule.org' > "${CODE_MASTER_SPACE}/o/${localOID}"
  links=(
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority1_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority2_develop_pem_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority1_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority2_develop_key_hash}>${pemOID}>${typeRID}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${directory_authority1_develop_pem_hash}>${directory_authority1_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${directory_authority2_develop_pem_hash}>${directory_authority2_develop_key_hash}>${Pkey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority1_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority2_develop_pem_hash}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority1_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority2_develop_pem_hash}>${localOID}>${localRID}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority1_develop_pem_hash}>${rsa1024}>${algoKey}"
    "nebule:link/2:0_0>${INIT_DATE}/l>${directory_authority2_develop_pem_hash}>${rsa1024}>${algoKey}"
  )
  for link in "${links[@]}"
  do
    sign_write_link "${link}" "${directory_authority1_develop_key_hash}" "${directory_authority1_develop_pem_hash}" 256 "${DIRECTORY_MASTER_SPACE}"
    sign_write_link "${link}" "${directory_authority2_develop_key_hash}" "${directory_authority2_develop_pem_hash}" 256 "${DIRECTORY_MASTER_SPACE}"
    sign_write_link "${link}" "${directory_authority1_develop_key_hash}" "${directory_authority1_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
    sign_write_link "${link}" "${directory_authority2_develop_key_hash}" "${directory_authority2_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
  done

  #echo -n 'rsa.1024' > "${SPACE}/o/${rsa1024}"
  #echo -n 'rsa.2048' > "${SPACE}/o/${rsa2048}"
  #echo -n 'rsa.4096' > "${SPACE}/o/${rsa4096}"

  echo ' > chmod masters folders'
  for SPACE in $ALL_SPACES
  do
    echo "   - ${SPACE}"
    sudo chown -R 1000:33 "${SPACE}/l/" "${SPACE}/o/"
    sudo chmod 775 "${SPACE}/l/" "${SPACE}/o/"
    sudo chmod 664 "${SPACE}/l/"* "${SPACE}/o/"*
  done

  echo ' > flush PHP sessions'
  sudo /usr/bin/rm -f /var/lib/php/sessions/*
}

function work_export()
{
  echo ' > work export codes'
  echo ' ! do nothing by now!'
}

function work_dev_deploy()
{
  echo ' > work dev deploy codes'
  current_date=$(date "+0%Y%m%d%H%M%S")
  echo " > date : ${current_date}"

  echo ' > copy code on test environment'
  for SPACE in $ALL_SPACES
  do
    echo "   - ${SPACE}"
    cp "${WORKSPACE}/bootstrap.php" "${SPACE}/index.php"
  done

  echo " > RID code branch : ${LIB_RID_CODE_BRANCH}"
  echo " > NID code branch : ${NID_CODE_BRANCH}"

  entityNameOID=$(echo -n 'develop' | sha256sum | cut -d' ' -f1)'.sha2.256'
  nameRID=$(echo -n 'nebule/objet/nom' | sha256sum | cut -d' ' -f1)'.sha2.256'

  echo ' > links'
  echo '   - code branch'
  link="nebule:link/2:0_0>${current_date}/l>${LIB_RID_CODE_BRANCH}>${NID_CODE_BRANCH}>${LIB_RID_CODE_BRANCH}"
  sign_write_link "${link}" "${code_authority1_develop_key_hash}" "${code_authority1_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
  echo '   - name'
  link="nebule:link/2:0_0>${current_date}/l>${NID_CODE_BRANCH}>${entityNameOID}>${nameRID}"
  sign_write_link "${link}" "${code_authority1_develop_key_hash}" "${code_authority1_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
}

function work_refresh()
{
  echo ' > work refresh codes'
  current_date=$(date "+0%Y%m%d%H%M%S")
  echo " > date : ${current_date}"

  phpOID=$(echo -n 'application/x-httpd-php' | sha256sum | cut -d' ' -f1)'.sha2.256'
  textOID=$(echo -n 'text/plain' | sha256sum | cut -d' ' -f1)'.sha2.256'
  typeRID=$(echo -n 'nebule/objet/type' | sha256sum | cut -d' ' -f1)'.sha2.256'
  autentNameOID=$(echo -n 'autent' | sha256sum | cut -d' ' -f1)'.sha2.256'
  entityNameOID=$(echo -n 'entity' | sha256sum | cut -d' ' -f1)'.sha2.256'
  sylabeNameOID=$(echo -n 'sylabe' | sha256sum | cut -d' ' -f1)'.sha2.256'
  klictyNameOID=$(echo -n 'klicty' | sha256sum | cut -d' ' -f1)'.sha2.256'
  messaeNameOID=$(echo -n 'messae' | sha256sum | cut -d' ' -f1)'.sha2.256'
  qantionNameOID=$(echo -n 'qantion' | sha256sum | cut -d' ' -f1)'.sha2.256'
  optionNameOID=$(echo -n 'option' | sha256sum | cut -d' ' -f1)'.sha2.256'
  uploadNameOID=$(echo -n 'upload' | sha256sum | cut -d' ' -f1)'.sha2.256'
  neblogNameOID=$(echo -n 'neblog' | sha256sum | cut -d' ' -f1)'.sha2.256'
  nameRID=$(echo -n 'nebule/objet/nom' | sha256sum | cut -d' ' -f1)'.sha2.256'
  autentSurnameOID=$(echo -n 'Au' | sha256sum | cut -d' ' -f1)'.sha2.256'
  entitySurnameOID=$(echo -n 'En' | sha256sum | cut -d' ' -f1)'.sha2.256'
  sylabeSurnameOID=$(echo -n 'Sy' | sha256sum | cut -d' ' -f1)'.sha2.256'
  klictySurnameOID=$(echo -n 'Kl' | sha256sum | cut -d' ' -f1)'.sha2.256'
  messaeSurnameOID=$(echo -n 'Me' | sha256sum | cut -d' ' -f1)'.sha2.256'
  qantionSurnameOID=$(echo -n 'Qa' | sha256sum | cut -d' ' -f1)'.sha2.256'
  optionSurnameOID=$(echo -n 'Op' | sha256sum | cut -d' ' -f1)'.sha2.256'
  uploadSurnameOID=$(echo -n 'Up' | sha256sum | cut -d' ' -f1)'.sha2.256'
  neblogSurnameOID=$(echo -n 'Ne' | sha256sum | cut -d' ' -f1)'.sha2.256'
  surnameRID=$(echo -n 'nebule/objet/surnom' | sha256sum | cut -d' ' -f1)'.sha2.256'
  imageRID=$(echo -n 'nebule/objet/image/reference' | sha256sum | cut -d' ' -f1)'.sha2.256'

  bootstrap_hash=$(sha256sum "${WORKSPACE}/bootstrap.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new bootstrap : ${bootstrap_hash}"
  cp "${WORKSPACE}/bootstrap.php" "${CODE_MASTER_SPACE}/o/${bootstrap_hash}"

  cat > "${WORKSPACE}/lib_nebule.php" << EOF
<?php
declare(strict_types=1);
namespace Nebule\Library;
EOF
  for F in "${WORKSPACE}/lib_nebule"/*
  do
    library_hash=$(sha256sum "${F}" | cut -d' ' -f1)'.sha2.256'
    bname=$(basename "${F}")
    bstat=$(stat -c '%y' "${F}" | cut -d' ' -f1-2 | cut -d. -f1 | tr -dc "0-9")
    { echo -e "\n\n";
      echo '// ========================================================================================';
      echo "//   ${bname}";
      echo "//   0${bstat}";
      echo "//   ${library_hash}";
      tail +4 "${F}" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection '; } >> "${WORKSPACE}/lib_nebule.php"
  done
  library_hash=$(sha256sum "${WORKSPACE}/lib_nebule.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new library : ${library_hash}"
  cp "${WORKSPACE}/lib_nebule.php" "/tmp/lib_nebule.php"
  mv "${WORKSPACE}/lib_nebule.php" "${CODE_MASTER_SPACE}/o/${library_hash}"

  cat "${WORKSPACE}/autent.php" > "/tmp/autent.php"
  { tail +4 "${WORKSPACE}/module_autent.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/lib_nebule/999_license.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
  } >> "/tmp/autent.php"
  autent_hash=$(sha256sum "/tmp/autent.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new autent : ${autent_hash}"
  cp "/tmp/autent.php" "${CODE_MASTER_SPACE}/o/${autent_hash}"

  cat "${WORKSPACE}/entity.php" > "/tmp/entity.php"
  { tail +4 "${WORKSPACE}/module_entities.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_groups.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_en-en.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_fr-fr.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_es-co.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/lib_nebule/999_license.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
  } >> "/tmp/entity.php"
  entity_hash=$(sha256sum "/tmp/entity.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new entity : ${entity_hash}"
  cp "/tmp/entity.php" "${CODE_MASTER_SPACE}/o/${entity_hash}"
  cp ~/Images/klicty_neige20100101.jpg "${CODE_MASTER_SPACE}/o/f6bc46330958c60be02d3d43613790427523c49bd4477db8ff9ca3a5f392b499.sha2.256"

  cat "${WORKSPACE}/sylabe.php" > "/tmp/sylabe.php"
  { tail +4 "${WORKSPACE}/module_manage.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_admin.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_objects.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_groups.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_entities.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_messages.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_neblog.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_qantion.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_fr-fr.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_en-en.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_es-co.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/lib_nebule/999_license.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
  } >> "/tmp/sylabe.php"
  sylabe_hash=$(sha256sum "/tmp/sylabe.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new sylabe : ${sylabe_hash}"
  cp "/tmp/sylabe.php" "${CODE_MASTER_SPACE}/o/${sylabe_hash}"
  cp ~/Images/bg1.jpg "${CODE_MASTER_SPACE}/o/906da8f91f664b5bff2b23fb3f8bad69d2641932031594a656de6ce618e3404d.sha2.256"
  cp ~/Images/fr-fr.png "${CODE_MASTER_SPACE}/o/b55cb8774839a5a894cecf77ce5e47db7fc114c2bc92e3dfc77cb9b4a8f488ac.sha2.256"
  cp ~/Images/en-en.png "${CODE_MASTER_SPACE}/o/7796077f1b865951946dd40ab852f6f4d21e702e7c4f47bd5fa6cb9ce94a4c5f.sha2.256"
  cp ~/Images/es-co.png "${CODE_MASTER_SPACE}/o/7425a5a9dfdaaa084fba0dff69b3a6267a90ef42cb0fa093d5a4b47a8bc062dd.sha2.256"

  cat "${WORKSPACE}/klicty.php" > "/tmp/klicty.php"
  { tail +4 "${WORKSPACE}/klicty.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/lib_nebule/999_license.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
  } >> "/tmp/klicty.php"
  klicty_hash=$(sha256sum "/tmp/klicty.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new klicty : ${klicty_hash}"
  cp "/tmp/klicty.php" "${CODE_MASTER_SPACE}/o/${klicty_hash}"

  cat "${WORKSPACE}/messae.php" > "/tmp/messae.php"
  { tail +4 "${WORKSPACE}/module_messages.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_admin.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_objects.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_groups.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_fr-fr.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_en-en.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_es-co.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/lib_nebule/999_license.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
  } >> "/tmp/messae.php"
  messae_hash=$(sha256sum "/tmp/messae.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new messae : ${messae_hash}"
  cp "/tmp/messae.php" "${CODE_MASTER_SPACE}/o/${messae_hash}"

  cat "${WORKSPACE}/qantion.php" > "/tmp/qantion.php"
  { tail +4 "${WORKSPACE}/module_qantion.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_fr-fr.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_en-en.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_es-co.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/lib_nebule/999_license.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
  } >> "/tmp/qantion.php"
  qantion_hash=$(sha256sum "/tmp/qantion.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new qantion : ${qantion_hash}"
  cp "/tmp/qantion.php" "${CODE_MASTER_SPACE}/o/${qantion_hash}"

  upload_hash=$(sha256sum "${WORKSPACE}/upload.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new upload : ${upload_hash}"
  cp "${WORKSPACE}/upload.php" "${CODE_MASTER_SPACE}/o/${upload_hash}"

  cat "${WORKSPACE}/neblog.php" > "/tmp/neblog.php"
  { tail +4 "${WORKSPACE}/module_neblog.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_fr-fr.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_en-en.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/module_lang_es-co.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
    tail +4 "${WORKSPACE}/lib_nebule/999_license.php" | grep -v '^use Nebule\\Library' | grep -v '^use Nebule\\Application' | grep -v '/** @noinspection ';
  } >> "/tmp/neblog.php"
  neblog_hash=$(sha256sum "/tmp/neblog.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new neblog : ${neblog_hash}"
  cp "/tmp/neblog.php" "${CODE_MASTER_SPACE}/o/${neblog_hash}"
  cp ~/Images/bg2.jpg "${CODE_MASTER_SPACE}/o/8c40708bd7d89ba9e0bdb74a5300632100d4b660a99a11608930cb0ff56e132a.sha2.256"

  option_hash=$(sha256sum "${WORKSPACE}/option.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new option : ${option_hash}"
  cp "${WORKSPACE}/option.php" "${CODE_MASTER_SPACE}/o/${option_hash}"

  belzbu_hash=$(sha256sum "${WORKSPACE}/belzbu.php" | cut -d' ' -f1)'.sha2.256'
  echo " > new belzbu : ${belzbu_hash}"
  cp "${WORKSPACE}/belzbu.php" "${CODE_MASTER_SPACE}/o/${belzbu_hash}"

  for module in module_admin module_autent module_entities module_groups module_manage module_messages module_neblog module_objects module_qantion module_lang_en-en module_lang_es-co module_lang_fr-fr
  do
    module_hash=$(sha256sum "${WORKSPACE}/${module}.php" | cut -d' ' -f1)'.sha2.256'
    varName=$(echo "${module}" | tr '-' '_')
    declare "${varName}"_hash="${module_hash}"
    echo " > new ${module} : ${module_hash}"
    cp "${WORKSPACE}/${module}.php" "${CODE_MASTER_SPACE}/o/${module_hash}"
  done

  echo ' > links'
  links=(
    # nodes
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_BOOTSTRAP}>${IID_INTERFACE_BOOTSTRAP}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_LIBRARY}>${IID_INTERFACE_LIBRARY}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_AUTENT}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_ENTITY}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_SYLABE}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_KLICTY}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_MESSAE}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_NEBLOG}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_QANTION}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_OPTION}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_APPLICATIONS}>${IID_INTERFACE_UPLOAD}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_admin_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_autent_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_entities_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_groups_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_manage_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_messages_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_neblog_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_objects_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES}>${module_qantion_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES_TRANSLATE}>${module_lang_en_en_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES_TRANSLATE}>${module_lang_es_co_hash}>${phpOID}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${INIT_DATE}/f>${LIB_RID_INTERFACE_MODULES_TRANSLATE}>${module_lang_fr_fr_hash}>${phpOID}>${NID_CODE_BRANCH}"
    # type mime = application/x-httpd-php
    "nebule:link/2:0_0>${current_date}/l>${bootstrap_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${library_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${autent_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${entity_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${sylabe_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${klicty_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${messae_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${neblog_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${qantion_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${option_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${upload_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_admin_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_autent_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_entities_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_groups_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_manage_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_messages_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_neblog_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_objects_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_qantion_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_lang_en_en_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_lang_es_co_hash}>${phpOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${module_lang_fr_fr_hash}>${phpOID}>${typeRID}"
    # nebule/objet/interface/web/php/bootstrap in develop branch
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_BOOTSTRAP}>${bootstrap_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_LIBRARY}>${library_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_AUTENT}>${autent_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_ENTITY}>${entity_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_SYLABE}>${sylabe_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_KLICTY}>${klicty_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_MESSAE}>${messae_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_NEBLOG}>${neblog_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_QANTION}>${qantion_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_OPTION}>${option_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/f>${IID_INTERFACE_UPLOAD}>${upload_hash}>${NID_CODE_BRANCH}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_AUTENT}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_ENTITY}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_SYLABE}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_KLICTY}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_MESSAE}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_NEBLOG}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_QANTION}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_OPTION}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_UPLOAD}>${LIB_RID_INTERFACE_APPLICATIONS_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_admin_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_autent_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_entities_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_groups_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_manage_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_messages_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_neblog_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_objects_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_qantion_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_lang_en_en_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_lang_es_co_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${module_lang_fr_fr_hash}>${LIB_RID_INTERFACE_MODULES_ACTIVE}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_AUTENT}>${LIB_RID_INTERFACE_APPLICATIONS_DIRECT}"
    # names
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_AUTENT}>${autentNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_AUTENT}>${autentSurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_ENTITY}>${entityNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_ENTITY}>${entitySurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_SYLABE}>${sylabeNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_SYLABE}>${sylabeSurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_KLICTY}>${klictyNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_KLICTY}>${klictySurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_MESSAE}>${messaeNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_MESSAE}>${messaeSurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_NEBLOG}>${neblogNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_NEBLOG}>${neblogSurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_QANTION}>${qantionNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_QANTION}>${qantionSurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_OPTION}>${optionNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_OPTION}>${optionSurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_UPLOAD}>${uploadNameOID}>${nameRID}"
    "nebule:link/2:0_0>${current_date}/l>${IID_INTERFACE_UPLOAD}>${uploadSurnameOID}>${surnameRID}"
    "nebule:link/2:0_0>${current_date}/l>${autentNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${autentSurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${entityNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${entitySurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${sylabeNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${sylabeSurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${klictyNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${klictySurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${messaeNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${messaeSurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${neblogNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${neblogSurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${qantionNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${qantionSurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${optionNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${optionSurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${uploadNameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/l>${uploadSurnameOID}>${textOID}>${typeRID}"
    "nebule:link/2:0_0>${current_date}/f>6e6562756c652f6f626a65742f656e74697465000000000000000000000000000000.none.272>94d672f309fcf437f0fa305337bdc89fbb01e13cff8d6668557e4afdacaea1e0.sha2.256>${imageRID}"
  )
  for link in "${links[@]}"
  do
    echo -n .
    sign_write_link "${link}" "${code_authority1_develop_key_hash}" "${code_authority1_develop_pem_hash}" 256 "${CODE_MASTER_SPACE}"
  done
  echo

  echo -n "autent" > "${CODE_MASTER_SPACE}/o/${autentNameOID}"
  echo -n "Au" > "${CODE_MASTER_SPACE}/o/${autentSurnameOID}"
  echo -n "entity" > "${CODE_MASTER_SPACE}/o/${entityNameOID}"
  echo -n "En" > "${CODE_MASTER_SPACE}/o/${entitySurnameOID}"
  echo -n "sylabe" > "${CODE_MASTER_SPACE}/o/${sylabeNameOID}"
  echo -n "Sy" > "${CODE_MASTER_SPACE}/o/${sylabeSurnameOID}"
  echo -n "klicty" > "${CODE_MASTER_SPACE}/o/${klictyNameOID}"
  echo -n "Kl" > "${CODE_MASTER_SPACE}/o/${klictySurnameOID}"
  echo -n "messae" > "${CODE_MASTER_SPACE}/o/${messaeNameOID}"
  echo -n "Me" > "${CODE_MASTER_SPACE}/o/${messaeSurnameOID}"
  echo -n "neblog" > "${CODE_MASTER_SPACE}/o/${neblogNameOID}"
  echo -n "Ne" > "${CODE_MASTER_SPACE}/o/${neblogSurnameOID}"
  echo -n "qantion" > "${CODE_MASTER_SPACE}/o/${qantionNameOID}"
  echo -n "Qa" > "${CODE_MASTER_SPACE}/o/${qantionSurnameOID}"
  echo -n "option" > "${CODE_MASTER_SPACE}/o/${optionNameOID}"
  echo -n "Op" > "${CODE_MASTER_SPACE}/o/${optionSurnameOID}"
  echo -n "upload" > "${CODE_MASTER_SPACE}/o/${uploadNameOID}"
  echo -n "Up" > "${CODE_MASTER_SPACE}/o/${uploadSurnameOID}"

  echo ' > chmod masters folders'
  for SPACE in $ALL_SPACES
  do
    echo "   - ${SPACE}"
    sudo chown -R 1000:33 "${SPACE}/l/" "${SPACE}/o/"
    sudo chmod 775 "${SPACE}/l/" "${SPACE}/o/"
    sudo chmod 664 "${SPACE}/l/"* "${SPACE}/o/"*
    rm "${SPACE}/l/mark" "${SPACE}/o/mark"
  done
}

function copy_to_test_instance()
{
  echo ' > copy to test instance'
  [ -d "${TESTINSTANCE}" ] && rm -rf "${TESTINSTANCE}"
  mkdir -p "${TESTINSTANCE}"
  cp -r "${CODE_MASTER_SPACE}/index.php" "${CODE_MASTER_SPACE}/o" "${CODE_MASTER_SPACE}/l" "${CODE_MASTER_SPACE}/c" "${TESTINSTANCE}/"
  #cd "${TESTINSTANCE}" || return
  #git add o/* l/*
  #cd "${CODE_MASTER_SPACE}" || return
}

function sign_write_link()
{
  link="${1}"
  key="${2}"
  eid="${3}"
  size="${4}"
  location="${5}"
  [ "${location}" == '' ] && location=$CODE_MASTER_SPACE

  logger "sign_write_link ${link} with ${eid}"
  slink=$(echo -n "${link}" | openssl dgst -hex -"sha${size}" -sign "${PUPPETMASTER_SPACE}/o/${key}" -passin "pass:${password_entity}" | cut -d ' ' -f2)
  flink="${link}_${eid}>${slink}.sha2.${size}"
  nid1=$(echo "${link}" | cut -d_ -f2 | cut -d/ -f2 | cut -d '>' -f2)
  nid2=$(echo "${link}" | cut -d_ -f2 | cut -d/ -f2 | cut -d '>' -f3)
  nid3=$(echo "${link}" | cut -d_ -f2 | cut -d/ -f2 | cut -d '>' -f4)
  nid4=$(echo "${link}" | cut -d_ -f2 | cut -d/ -f2 | cut -d '>' -f5)
  [ "${nid1}" != '' ] && touch "${location}/l/${nid1}" && [[ $(grep "${flink}" "${location}/l/${nid1}") == '' ]] && echo "${flink}" >> "${location}/l/${nid1}"
  [ "${nid2}" != '' ] && touch "${location}/l/${nid2}" && [[ $(grep "${flink}" "${location}/l/${nid2}") == '' ]] && echo "${flink}" >> "${location}/l/${nid2}"
  [ "${nid3}" != '' ] && touch "${location}/l/${nid3}" && [[ $(grep "${flink}" "${location}/l/${nid3}") == '' ]] && echo "${flink}" >> "${location}/l/${nid3}"
  [ "${nid4}" != '' ] && touch "${location}/l/${nid4}" && [[ $(grep "${flink}" "${location}/l/${nid4}") == '' ]] && echo "${flink}" >> "${location}/l/${nid4}"
  echo "${flink}" >> "l/h"
}

# Recherche ou demande le mot de passe de l'entité.
#if [ -f ~/priv/default.password ]
#then
#  password_entity=$(cat ~/priv/default.password)
#else
#  read -r -s -p ' ? password : ' password_entity
#fi
#echo ''

# Extrait les clés publiques.
echo " > extract pub keys"
puppetmaster_develop_pem=$(        echo -n "${puppetmaster_develop_key}"         | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export puppetmaster_develop_pem
security_authority1_develop_pem=$( echo -n "${security_authority1_develop_key}"  | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export security_authority1_develop_pem
security_authority2_develop_pem=$( echo -n "${security_authority2_develop_key}"  | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export security_authority2_develop_pem
code_authority1_develop_pem=$(     echo -n "${code_authority1_develop_key}"      | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export code_authority1_develop_pem
code_authority2_develop_pem=$(     echo -n "${code_authority2_develop_key}"      | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export code_authority2_develop_pem
time_authority1_develop_pem=$(     echo -n "${time_authority1_develop_key}"      | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export time_authority1_develop_pem
time_authority2_develop_pem=$(     echo -n "${time_authority2_develop_key}"      | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export time_authority2_develop_pem
directory_authority1_develop_pem=$(echo -n "${directory_authority1_develop_key}" | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export directory_authority1_develop_pem
directory_authority2_develop_pem=$(echo -n "${directory_authority2_develop_key}" | openssl rsa -outform PEM -pubout -passin "pass:${password_entity}")
export directory_authority2_develop_pem

puppetmaster_develop_key_hash=$(        echo -n "${puppetmaster_develop_key}"         | sha256sum | cut -d' ' -f1)'.sha2.256'
export puppetmaster_develop_key_hash
puppetmaster_develop_pem_hash=$(        echo -n "${puppetmaster_develop_pem}"         | sha256sum | cut -d' ' -f1)'.sha2.256'
export puppetmaster_develop_pem_hash

security_authority1_develop_key_hash=$( echo -n "${security_authority1_develop_key}"  | sha256sum | cut -d' ' -f1)'.sha2.256'
export security_authority1_develop_key_hash
security_authority1_develop_pem_hash=$( echo -n "${security_authority1_develop_pem}"  | sha256sum | cut -d' ' -f1)'.sha2.256'
export security_authority1_develop_pem_hash
code_authority1_develop_key_hash=$(     echo -n "${code_authority1_develop_key}"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export code_authority1_develop_key_hash
code_authority1_develop_pem_hash=$(     echo -n "${code_authority1_develop_pem}"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export code_authority1_develop_pem_hash
time_authority1_develop_key_hash=$(     echo -n "${time_authority1_develop_key}"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export time_authority1_develop_key_hash
time_authority1_develop_pem_hash=$(     echo -n "${time_authority1_develop_pem}"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export time_authority1_develop_pem_hash
directory_authority1_develop_key_hash=$(echo -n "${directory_authority1_develop_key}" | sha256sum | cut -d' ' -f1)'.sha2.256'
export directory_authority1_develop_key_hash
directory_authority1_develop_pem_hash=$(echo -n "${directory_authority1_develop_pem}" | sha256sum | cut -d' ' -f1)'.sha2.256'
export directory_authority1_develop_pem_hash

security_authority2_develop_key_hash=$( echo -n "${security_authority2_develop_key}"  | sha256sum | cut -d' ' -f1)'.sha2.256'
export security_authority2_develop_key_hash
security_authority2_develop_pem_hash=$( echo -n "${security_authority2_develop_pem}"  | sha256sum | cut -d' ' -f1)'.sha2.256'
export security_authority2_develop_pem_hash
code_authority2_develop_key_hash=$(     echo -n "${code_authority2_develop_key}"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export code_authority2_develop_key_hash
code_authority2_develop_pem_hash=$(     echo -n "${code_authority2_develop_pem}"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export code_authority2_develop_pem_hash
time_authority2_develop_key_hash=$(     echo -n "${time_authority2_develop_key}"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export time_authority2_develop_key_hash
time_authority2_develop_pem_hash=$(     echo -n "${time_authority2_develop_pem}"      | sha256sum | cut -d' ' -f1)'.sha2.256'
export time_authority2_develop_pem_hash
directory_authority2_develop_key_hash=$(echo -n "${directory_authority2_develop_key}" | sha256sum | cut -d' ' -f1)'.sha2.256'
export directory_authority2_develop_key_hash
directory_authority2_develop_pem_hash=$(echo -n "${directory_authority2_develop_pem}" | sha256sum | cut -d' ' -f1)'.sha2.256'
export directory_authority2_develop_pem_hash

function mode_loop
{
  echo ' > mode loop'
  loop_type='r'
  while true
  do
  
  echo ' = wait'
  read -r -n 1 -p '[r] refresh codes / [d] dev deploy codes  / [e] export codes / [f] reinit full / [q] quit : ' loop_type
  echo -e "\n"
  
  cd $CODE_MASTER_SPACE || return 1
  cd $CODE_MASTER_SPACE || exit 1
  
  case "${loop_type}" in
    f) work_full_reinit; work_dev_deploy; work_refresh; copy_to_test_instance;;
    d) work_dev_deploy; work_refresh; copy_to_test_instance;;
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
    f) work_full_reinit; work_dev_deploy; work_refresh; copy_to_test_instance;;
    d) work_dev_deploy; work_refresh; copy_to_test_instance;;
    e) work_export;;
    r) work_refresh;;
  esac
  echo ' > end'
}

function main
{
  echo ' > main'

  echo "   - puppetmaster        : ${puppetmaster_develop_pem_hash}"
  echo "     - key               : ${puppetmaster_develop_key_hash}"
  echo "   - security authority  : ${security_authority1_develop_pem_hash}"
  echo "     - key               : ${security_authority1_develop_key_hash}"
  echo "   - code authority      : ${code_authority1_develop_pem_hash}"
  echo "     - key               : ${code_authority1_develop_key_hash}"
  echo "   - time authority      : ${time_authority1_develop_pem_hash}"
  echo "     - key               : ${time_authority1_develop_key_hash}"
  echo "   - directory authority : ${directory_authority1_develop_pem_hash}"
  echo "     - key               : ${directory_authority1_develop_key_hash}"

  if [ "${1}" == '' ]
  then
    mode_loop
  else
    mode_once "${1}"
  fi
}

main "${1}"
