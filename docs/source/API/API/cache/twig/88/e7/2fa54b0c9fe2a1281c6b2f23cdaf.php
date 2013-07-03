<?php

/* tree.twig */
class __TwigTemplate_88e72fa54b0c9fe2a1281c6b2f23cdaf extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "var tree = ";
        echo $this->getAttribute($this, "element", array(0 => (isset($context["tree"]) ? $context["tree"] : $this->getContext($context, "tree"))), "method");
        echo "

";
    }

    // line 3
    public function getelement($_tree = null)
    {
        $context = $this->env->mergeGlobals(array(
            "tree" => $_tree,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 5
            echo "[";
            // line 6
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["tree"]) ? $context["tree"] : $this->getContext($context, "tree")));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                // line 7
                echo "[";
                // line 8
                echo twig_jsonencode_filter($this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 0, array(), "array"));
                echo ",";
                // line 9
                echo (($this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 1, array(), "array")) ? (twig_jsonencode_filter((((!$this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 2, array(), "array"))) ? ($this->env->getExtension('sami')->pathForClass($context, $this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 1, array(), "array"))) : ($this->env->getExtension('sami')->pathForNamespace($context, $this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 1, array(), "array")))))) : (""));
                echo ",";
                // line 10
                echo twig_jsonencode_filter(((((!$this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 2, array(), "array")) && $this->getAttribute($this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 1, array(), "array"), "parent"))) ? ((" < " . $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 1, array(), "array"), "parent"), "shortname"))) : ("")));
                echo ",
                ";
                // line 11
                echo $this->getAttribute($this, "element", array(0 => $this->getAttribute((isset($context["element"]) ? $context["element"] : $this->getContext($context, "element")), 2, array(), "array")), "method");
                // line 12
                echo "]";
                // line 13
                echo (($this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "last")) ? ("") : (","));
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 15
            echo "        ]";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "tree.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 13,  27 => 3,  120 => 35,  106 => 33,  98 => 29,  92 => 27,  89 => 26,  86 => 25,  77 => 21,  59 => 8,  48 => 10,  127 => 64,  122 => 61,  109 => 59,  24 => 4,  93 => 27,  74 => 20,  69 => 11,  63 => 5,  57 => 7,  97 => 27,  61 => 26,  45 => 7,  144 => 39,  136 => 36,  129 => 35,  125 => 33,  118 => 32,  114 => 30,  105 => 58,  101 => 30,  95 => 28,  88 => 50,  72 => 18,  66 => 18,  55 => 14,  26 => 3,  43 => 8,  41 => 9,  21 => 4,  379 => 58,  363 => 56,  358 => 55,  355 => 54,  350 => 53,  333 => 52,  331 => 51,  329 => 50,  318 => 49,  303 => 46,  291 => 45,  265 => 40,  261 => 39,  258 => 37,  255 => 35,  253 => 34,  236 => 33,  234 => 32,  222 => 31,  211 => 28,  205 => 27,  199 => 26,  185 => 25,  174 => 22,  168 => 21,  162 => 20,  148 => 19,  135 => 16,  133 => 15,  126 => 13,  119 => 11,  117 => 10,  104 => 32,  53 => 12,  42 => 6,  37 => 8,  34 => 4,  25 => 6,  19 => 1,  110 => 32,  103 => 28,  99 => 55,  90 => 27,  87 => 15,  83 => 23,  79 => 21,  64 => 16,  62 => 9,  58 => 15,  52 => 13,  49 => 11,  46 => 9,  40 => 6,  80 => 22,  76 => 20,  71 => 12,  60 => 13,  56 => 13,  50 => 11,  31 => 5,  94 => 26,  91 => 25,  84 => 8,  81 => 22,  75 => 20,  70 => 19,  68 => 18,  65 => 10,  47 => 10,  44 => 9,  38 => 5,  33 => 5,  22 => 8,  51 => 12,  39 => 6,  35 => 4,  32 => 6,  29 => 8,  28 => 5,);
    }
}
