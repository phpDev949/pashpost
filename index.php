<?php

$prefix[0] = '+ * * 22 26 53 + * 66 8 + + * * * 7 76 25 44 78 100';
$prefix[1] = '+ + + 27 38 81 + * * 48 33 53 + * 91 53 + * 82 14 96';
$prefix[2] = '+ 57 + * 14 71 + * * 86 39 24 + * 48 3 * * 92 16 60';

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
*      (57 + ((14 * 71) + (((86 * 39) * 24) + ((48 * 3) + ((92 * 16) * 60))))) = 170011
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
        $operators = array('*' => 'Mult', '+' => 'Plus');
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

class PrefixNodeOperatorPlus extends PrefixNodeOperator
{
    public function evaluate()
    {
        return $this->left->evaluate() + $this->right->evaluate();
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
