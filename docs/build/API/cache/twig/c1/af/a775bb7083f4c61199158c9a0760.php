<?php

/* tree.twig */
class __TwigTemplate_c1afa775bb7083f4c61199158c9a0760 extends Twig_Template
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
        echo twig_escape_filter($this->env, $this->getAttribute($this, "element", array(0 => $this->getContext($context, "tree")), "method"), "html", null, true);
        echo "

";
    }

    // line 3
    public function getelement($tree = null)
    {
        $context = $this->env->mergeGlobals(array(
            "tree" => $tree,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 5
            echo "[";
            // line 6
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "tree"));
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
                echo twig_jsonencode_filter($this->getAttribute($this->getContext($context, "element"), 0, array(), "array"));
                echo ",";
                // line 9
                echo (($this->getAttribute($this->getContext($context, "element"), 1, array(), "array")) ? (twig_jsonencode_filter((((!$this->getAttribute($this->getContext($context, "element"), 2, array(), "array"))) ? ($this->env->getExtension('sami')->pathForClass($context, $this->getAttribute($this->getContext($context, "element"), 1, array(), "array"))) : ($this->env->getExtension('sami')->pathForNamespace($context, $this->getAttribute($this->getContext($context, "element"), 1, array(), "array")))))) : (""));
                echo ",";
                // line 10
                echo twig_jsonencode_filter(((((!$this->getAttribute($this->getContext($context, "element"), 2, array(), "array")) && $this->getAttribute($this->getAttribute($this->getContext($context, "element"), 1, array(), "array"), "parent"))) ? ((" < " . $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "element"), 1, array(), "array"), "parent"), "shortname"))) : ("")));
                echo ",
                ";
                // line 11
                echo $this->getAttribute($this, "element", array(0 => $this->getAttribute($this->getContext($context, "element"), 2, array(), "array")), "method");
                // line 12
                echo "]";
                // line 13
                echo (($this->getAttribute($this->getContext($context, "loop"), "last")) ? ("") : (","));
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
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 15
            echo "        ]";
        } catch(Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ob_get_clean();
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
        return array (  71 => 13,  25 => 3,  118 => 35,  104 => 33,  96 => 29,  90 => 27,  87 => 26,  84 => 25,  75 => 21,  57 => 8,  46 => 10,  125 => 64,  120 => 61,  107 => 59,  22 => 4,  91 => 27,  72 => 20,  67 => 11,  61 => 5,  55 => 7,  95 => 27,  59 => 26,  43 => 7,  142 => 39,  134 => 36,  127 => 35,  123 => 33,  116 => 32,  112 => 30,  103 => 58,  99 => 30,  93 => 28,  86 => 50,  70 => 18,  64 => 18,  53 => 14,  24 => 3,  41 => 8,  39 => 9,  19 => 4,  377 => 58,  361 => 56,  356 => 55,  353 => 54,  348 => 53,  331 => 52,  329 => 51,  327 => 50,  316 => 49,  301 => 46,  289 => 45,  263 => 40,  259 => 39,  256 => 37,  253 => 35,  251 => 34,  234 => 33,  232 => 32,  220 => 31,  209 => 28,  203 => 27,  197 => 26,  183 => 25,  172 => 22,  166 => 21,  160 => 20,  146 => 19,  133 => 16,  131 => 15,  124 => 13,  117 => 11,  115 => 10,  102 => 32,  51 => 12,  40 => 6,  35 => 8,  32 => 4,  23 => 6,  17 => 1,  108 => 32,  101 => 28,  97 => 55,  88 => 27,  85 => 15,  81 => 23,  77 => 21,  62 => 16,  60 => 9,  56 => 15,  50 => 13,  47 => 11,  44 => 9,  38 => 6,  78 => 22,  74 => 20,  69 => 12,  58 => 13,  54 => 13,  48 => 11,  29 => 5,  92 => 26,  89 => 25,  82 => 8,  79 => 22,  73 => 20,  68 => 19,  66 => 18,  63 => 10,  45 => 10,  42 => 9,  36 => 5,  31 => 5,  20 => 8,  49 => 12,  37 => 6,  33 => 4,  30 => 6,  27 => 8,  26 => 5,);
    }
}
