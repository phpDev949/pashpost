<?php

$prefix[0] = '+ * * 22 26 53 + * 66 8 + + * * * 7 76 25 44 78 100';
$prefix[1] = '+ + + 27 38 81 + * * 48 33 53 + * 91 53 + * 82 14 96';
$prefix[2] = '- 93 - / 10 7 - + + + / / / 66 50 10 32 35 33 12 4';
$prefix[3] = '+ 57 + * 14 71 + * * 86 39 24 + * 48 3 * * 92 16 60';
$prefix[4] = '/ 32 / * 70 44 / * 77 89 - - + + * 12 45 15 47 90 50';
$prefix[5] = '+ + 85.21 5.42 - * 34.96 37.59 - * 60.15 94.31 - * 47.53 59.03 / / 50.54 14.01 44';
$prefix[6] = '- 0.61 + 38.2 / 46.08 - * 71.23 85.53 + 68.92 / 61.41 + * 46.79 88.71 / 9.93 27';
$prefix[7] = '- 50.08 / 47.99 + * 68.32 73.39 + / 80.06 46.73 / / / * 13.55 94.26 30.13 25.74 41';


for ($i=0;$i<count($prefix);$i++) {
  $parser = new PrefixParser($prefix[$i]);
  $node = $parser->parse();
  echo $node, "\n"; 
  echo ' = ', $node->evaluate(), "<br>\n"; 
}

/*** results below
*
*      (((22 * 26) * 53) + ((66 * 8) + (((((7 * 76) * 25) * 44) + 78) + 100))) = 616222
*      (((27 + 38) + 81) + (((48 * 33) * 53) + ((91 * 53) + ((82 * 14) + 96)))) = 90165
* .    (93 - ((10 / 7) - (((((((66 / 50) / 10) / 32) + 35) + 33) + 12) - 4))) = 167.57555357143
*      (57 + ((14 * 71) + (((86 * 39) * 24) + ((48 * 3) + ((92 * 16) * 60))))) = 170011
*      (32 / ((70 * 44) / ((77 * 89) / (((((12 * 45) + 15) + 47) - 90) - 50)))) = 0.15411255411255
*      ((85.21 + 5.42) + ((34.96 * 37.59) - ((60.15 * 94.31) - ((47.53 * 59.03) - ((50.54 / 14.01) / 44))))) = -1462.3561868925
*      (0.61 - (38.2 + (46.08 / ((71.23 * 85.53) - (68.92 + (61.41 / ((46.79 * 88.71) + (9.93 / 27)))))))) = -37.597650206137
*      (50.08 - (47.99 / ((68.32 * 73.39) + ((80.06 / 46.73) + ((((13.55 * 94.26) / 30.13) / 25.74) / 41))))) = 50.070432154431
*/


class PrefixParser extends IteratorIterator
{
    public function __construct($prefix)
    {
        $tokens = new ArrayIterator(preg_split('/\s/', $prefix, 0, PREG_SPLIT_NO_EMPTY));
        parent::__construct($tokens);
    }

    /**
     * @return PrefixNode
     */
    public function current()
    {
        $string = parent::current();
        parent::next();
        $operators = array('*' => 'Mult', '/' => 'Divide', '+' => 'Plus', '-' => 'Minus', '%' => 'Modulus');
        $class = 'PrefixNode' . (isset($operators[$string]) ? 'Operator' . $operators[$string] : 'Value');
        $node = new $class($string);
        if ($node instanceof PrefixNodeOperator) {
            $node->setLeft($this->current());
            $node->setRight($this->current());
        }
        return $node;
    }

    public function __toString()
    {
        return (string)$this->parse();
    }

    public function parse()
    {
        $this->rewind();
        return $this->current();
    }
}
abstract class PrefixNode
{
    abstract function evaluate();
}

abstract class PrefixNodeOperator extends PrefixNode
{
    private $operator;
    protected $left;
    protected $right;

    public function __construct($operator)
    {
        $this->operator = $operator;
    }

    public function setLeft(PrefixNode $node)
    {
        $this->left = $node;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function setRight(PrefixNode $node)
    {
        $this->right = $node;
    }

    public function getRight()
    {
        return $this->right;
    }

    public function __toString()
    {
        return sprintf('(%s %s %s)', $this->left, $this->operator, $this->right);
    }
}

class PrefixNodeOperatorMult extends PrefixNodeOperator
{
    public function evaluate()
    {
        return $this->left->evaluate() * $this->right->evaluate();
    }
}

class PrefixNodeOperatorDivide extends PrefixNodeOperator
{
    public function evaluate()
    {
        return $this->left->evaluate() / $this->right->evaluate();
    }
}

class PrefixNodeOperatorPlus extends PrefixNodeOperator
{
    public function evaluate()
    {
        return $this->left->evaluate() + $this->right->evaluate();
    }
}

class PrefixNodeOperatorMinus extends PrefixNodeOperator
{
    public function evaluate()
    {
        return $this->left->evaluate() - $this->right->evaluate();
    }
}

class PrefixNodeOperatorModulus extends PrefixNodeOperator
{
    public function evaluate()
    {
        return $this->left->evaluate() % $this->right->evaluate();
    }
}

class PrefixNodeValue extends PrefixNode
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string)$this->value;
    }

    public function evaluate()
    {
        return $this->value;
    }
}
?>
