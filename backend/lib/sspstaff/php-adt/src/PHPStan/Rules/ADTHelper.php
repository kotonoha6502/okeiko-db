<?php

namespace SspStaff\ADT\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Broker\Broker;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

class ADTHelper
{
    /** @var Broker */
    private $broker;
    /**
     * @var PhpDocParser
     */
    private $phpDocParser;
    /**
     * @var Lexer
     */
    private $phpDocLexer;

    public function __construct(Broker $broker, PhpDocParser  $phpDocParser, Lexer $phpDocLexer) {
        $this->broker = $broker;
        $this->phpDocParser = $phpDocParser;
        $this->phpDocLexer = $phpDocLexer;
    }

    public function isADTClass(Node $node) :bool {
        if (! $node instanceof Node\Stmt\Class_) {
            return false;
        }

        return $this->isADTClassByClassName($node->namespacedName->toString());
    }

    public function isADTCaseClass (Node $node) :bool {
        if (!$node instanceof Node\Stmt\Class_) {
            return false;
        }

        if ($node->extends === null) {
            return false;
        }

        $extendClass = (string)$node->extends;
        return $this->isADTClassByClassName($extendClass);
    }

    public function isADTClassByClassName (string $classname) :bool {
        $class = $this->broker->getClass($classname);
        $docComment = $class->getNativeReflection()->getDocComment();
        $docTokens = new TokenIterator($this->phpDocLexer->tokenize($docComment));
        $docNode = $this->phpDocParser->parse($docTokens);

        return count($docNode->getTagsByName('@ssp-adt')) > 0;
    }


}
