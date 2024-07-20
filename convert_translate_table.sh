#!/bin/bash
F=$1
[ "${F}" == '' ] && echo 'give name' && return 1 2>&-
[ "${F}" == '' ] && exit 1

echo 'CONST TRANSLATE_TABLE = ['

for L in 'fr-fr' 'en-en' 'es-co'; do
  echo "    '${L}' => ["
  grep -A10000 '_initTable_DEPRECATED()' ${F} | grep -- "\$this->_table\['$L'\]" | sed "s/\$this->_table\['$L'\]\[//" | sed "s/\] = / => /" | sed "s/\]=/ => /" | sed "s/;$/,/"
  echo '    ],'
done

echo '];'
