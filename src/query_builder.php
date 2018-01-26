<?php

class query_builder
{
	function __construct()
	{

	}

	function buildClause($arg_base = null, $arg_clause = array())
	{
		$where = is_null($arg_base) ? ' WHERE TRUE ' : $arg_base;

		foreach($arg_clause as $outter_element)
		{
			$where 	.= ' AND (';
			$i 		= 0;
			$total_clause = array_size($outter_element);

			foreach($outter_element as $inner_element)
			{
				$inner_element->operator 	= mysql_real_escape_string($inner_element->operator);
				$inner_element->table 		= mysql_real_escape_string($inner_element->table);
				$inner_element->column 		= mysql_real_escape_string($inner_element->column);
				$inner_element->value 		= mysql_real_escape_string($inner_element->value);

				switch( strtolower($inner_element->operator) )
				{
					case 'gt':
					case 'greater_than':
						$where .= $inner_element->table . '.' . $inner_element->column . ' > "' . $inner_element->value . '" ';
						break;

					case 'gte':
					case 'greater_than_equal_to':
						$where .= $inner_element->table . '.' . $inner_element->column . ' >= "' . $inner_element->value . '" ';
						break;

					case 'lt':
					case 'less_than':
						$where .= $inner_element->table . '.' . $inner_element->column . ' < "' . $inner_element->value . '" ';
						break;

					case 'lte':
					case 'less_than_equal_to':
						$where .= $inner_element->table . '.' . $inner_element->column . ' <= "' . $inner_element->value . '" ';
						break;

					case 'sw':
					case 'starts_with':
						$where .= $inner_element->table . '.' . $inner_element->column . ' LIKE "%' . $inner_element->value . '" ';
						break;

					case 'ew':
					case 'ends_with':
						$where .= $inner_element->table . '.' . $inner_element->column . ' LIKE "' . $inner_element->value . '%" ';
						break;

					case 'pm':
					case 'partial_match':
					case 'contains':
						$where .= $inner_element->table . '.' . $inner_element->column . ' LIKE "%' . $inner_element->value . '%" ';
						break;
					case 'nn':
					case 'not_null':
						$where .= $inner_element->table . '.' . $inner_element->column . ' is not null';
						break;

					case 'in':
						$value_is_array 	= is_array($inner_element->value);
						$group 				= ''; 

						if( $value_is_array ){
							$group 	= '("' . implode('","', $inner_element->value) . '")';
						}
						else {
							$group 	= '(' . $inner_element->value . ')';
						}
						$where .= $inner_element->table . '.' . $inner_element->column . ' IN ' . $group;
						break;

					case 'not_in':
						$value_is_array 	= is_array($inner_element->value);
						$group 				= ''; 

						if( $value_is_array ){
							$group 	= '("' . implode('","', $inner_element->value) . '")';
						}
						else {
							$group 	= '(' . $inner_element->value . ')';
						}
						$where .= $inner_element->table . '.' . $inner_element->column . ' NOT IN ' . $group;
						break;

					default:
						$where .= $inner_element->table . '.' . $inner_element->column . ' = "' . $inner_element->value . '" ';
						break;
				}
				if( $i < $total_clause) 
				{
					$where .= ' OR ';
				}
				else
				{
					$where .= '';
				}
			}
			$where .= ')';
			return $where;
		}
	}
}
?>