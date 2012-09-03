<?php

/* namespace.twig */
class __TwigTemplate_c0fb1aefe71f983057b0b726879f8ad9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout/base.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body_class' => array($this, 'block_body_class'),
            'header' => array($this, 'block_header'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["__internal_ff2b118394f3774090df3e995c66bf1aabb901bc"] = $this->env->loadTemplate("macros.twig");
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, $this->getContext($context, "namespace"), "html", null, true);
        echo " | ";
        $this->displayParentBlock("title", $context, $blocks);
    }

    // line 7
    public function block_body_class($context, array $blocks = array())
    {
        echo "frame";
    }

    // line 9
    public function block_header($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"header\">
        <h1>";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "project"), "config", array(0 => "title"), "method"), "html", null, true);
        echo "</h1>

        <ul>
            <li><a href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('sami')->pathForStaticFile($context, "classes-frame.html"), "html", null, true);
        echo "\">Classes</a></li>
            <li><a href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('sami')->pathForStaticFile($context, "namespaces-frame.html"), "html", null, true);
        echo "\">Namespaces</a></li>
        </ul>
    </div>
";
    }

    // line 20
    public function block_content($context, array $blocks = array())
    {
        // line 21
        echo "    <h1>";
        echo $context["__internal_ff2b118394f3774090df3e995c66bf1aabb901bc"]->getnamespace_link($this->getContext($context, "namespace"), array("target" => "main"));
        echo "</h1>

    ";
        // line 23
        if ($this->getContext($context, "classes")) {
            // line 24
            echo "        <ul>
            ";
            // line 25
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "classes"));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 26
                echo "                <li>";
                echo $context["__internal_ff2b118394f3774090df3e995c66bf1aabb901bc"]->getclass_link($this->getContext($context, "class"), array("target" => "main"));
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 28
            echo "        </ul>
    ";
        }
        // line 30
        echo "
    ";
        // line 31
        if ($this->getContext($context, "interfaces")) {
            // line 32
            echo "        <h2>Interfaces</h2>
        <ul>
            ";
            // line 34
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "interfaces"));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 35
                echo "                <li>";
                echo $context["__internal_ff2b118394f3774090df3e995c66bf1aabb901bc"]->getclass_link($this->getContext($context, "class"), array("target" => "main"));
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 37
            echo "        </ul>
    ";
        }
        // line 39
        echo "
    ";
        // line 40
        if ($this->getContext($context, "exceptions")) {
            // line 41
            echo "        <h2>Exceptions</h2>
        <ul>
            ";
            // line 43
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "exceptions"));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 44
                echo "                <li>";
                echo $context["__internal_ff2b118394f3774090df3e995c66bf1aabb901bc"]->getclass_link($this->getContext($context, "class"), array("target" => "main"));
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 46
            echo "        </ul>
    ";
        }
    }

    public function getTemplateName()
    {
        return "namespace.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  148 => 46,  139 => 44,  135 => 43,  129 => 40,  126 => 39,  122 => 37,  113 => 35,  109 => 34,  105 => 32,  100 => 30,  83 => 25,  80 => 24,  71 => 13,  25 => 3,  118 => 35,  104 => 33,  96 => 28,  90 => 27,  87 => 26,  84 => 25,  75 => 21,  57 => 14,  46 => 10,  125 => 64,  120 => 61,  107 => 59,  22 => 4,  91 => 27,  72 => 21,  67 => 11,  61 => 15,  55 => 7,  95 => 27,  59 => 26,  43 => 7,  142 => 39,  134 => 36,  127 => 35,  123 => 33,  116 => 32,  112 => 30,  103 => 31,  99 => 30,  93 => 28,  86 => 50,  70 => 18,  64 => 18,  53 => 14,  24 => 3,  41 => 8,  39 => 7,  19 => 4,  377 => 58,  361 => 56,  356 => 55,  353 => 54,  348 => 53,  331 => 52,  329 => 51,  327 => 50,  316 => 49,  301 => 46,  289 => 45,  263 => 40,  259 => 39,  256 => 37,  253 => 35,  251 => 34,  234 => 33,  232 => 32,  220 => 31,  209 => 28,  203 => 27,  197 => 26,  183 => 25,  172 => 22,  166 => 21,  160 => 20,  146 => 19,  133 => 16,  131 => 41,  124 => 13,  117 => 11,  115 => 10,  102 => 32,  51 => 11,  40 => 6,  35 => 8,  32 => 4,  23 => 6,  17 => 1,  108 => 32,  101 => 28,  97 => 55,  88 => 27,  85 => 15,  81 => 23,  77 => 21,  62 => 16,  60 => 9,  56 => 15,  50 => 13,  47 => 11,  44 => 9,  38 => 6,  78 => 23,  74 => 20,  69 => 20,  58 => 13,  54 => 13,  48 => 10,  29 => 5,  92 => 26,  89 => 25,  82 => 8,  79 => 22,  73 => 20,  68 => 19,  66 => 18,  63 => 10,  45 => 9,  42 => 9,  36 => 5,  31 => 5,  20 => 8,  49 => 12,  37 => 6,  33 => 4,  30 => 6,  27 => 8,  26 => 3,);
    }
}
