<?php

namespace concepture\core\enum;

class DbQueryEnum extends CEnum
{
    const JOIN = 'JOIN';
    const LEFT_JOIN = 'LEFT JOIN';
    const RIGHT_JOIN = 'RIGHT JOIN';
    const INNER_JOIN = 'INNER JOIN';
    const OUTER_JOIN = 'OUTER JOIN';

    const OPERATOR_AND = 'AND';
    const OPERATOR_OR = 'OR';
}
