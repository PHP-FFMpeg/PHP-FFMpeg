<?php

/* pages/namespace.twig */
class __TwigTemplate_f84d6231490fc53718fc429c4e715f9c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body_class' => array($this, 'block_body_class'),
            'content_header' => array($this, 'block_content_header'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->getContext($context, "page_layout"));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["__internal_f84d6231490fc53718fc429c4e715f9c_1"] = $this->env->loadTemplate("macros.twig");
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
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
        echo "overview";
    }

    // line 9
    public function block_content_header($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"type\">Namespace</div>
    <h1>";
        // line 11
        echo twig_escape_filter($this->env, $this->getContext($context, "namespace"), "html", null, true);
        echo "</h1>
";
    }

    // line 14
    public function block_content($context, array $blocks = array())
    {
        // line 15
        echo "    ";
        if ($this->getContext($context, "classes")) {
            // line 16
            echo "        <table>
            ";
            // line 17
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "classes"));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 18
                echo "                <tr>
                    <td>";
                // line 19
                echo $context["__internal_f84d6231490fc53718fc429c4e715f9c_1"]->getclass_link($this->getContext($context, "class"));
                echo "</td>
                    <td class=\"last\">";
                // line 20
                echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "class"), "shortdesc"), $this->getContext($context, "class")), "html", null, true));
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 23
            echo "        </table>
    ";
        }
        // line 25
        echo "
    ";
        // line 26
        if ($this->getContext($context, "interfaces")) {
            // line 27
            echo "        <h2>Interfaces</h2>
        <table>
            ";
            // line 29
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "interfaces"));
            foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                // line 30
                echo "                <tr>
                    <td>";
                // line 31
                echo $context["__internal_f84d6231490fc53718fc429c4e715f9c_1"]->getclass_link($this->getContext($context, "interface"));
                echo "</td>
                    <td class=\"last\">";
                // line 32
                echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "interface"), "shortdesc"), $this->getContext($context, "interface")), "html", null, true));
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 35
            echo "        </table>
    ";
        }
        // line 37
        echo "
    ";
        // line 38
        if ($this->getContext($context, "exceptions")) {
            // line 39
            echo "        <h2>Exceptions</h2>
        <table>
            ";
            // line 41
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "exceptions"));
            foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
                // line 42
                echo "                <tr>
                    <td>";
                // line 43
                echo $context["__internal_f84d6231490fc53718fc429c4e715f9c_1"]->getclass_link($this->getContext($context, "exception"));
                echo "</td>
                    <td class=\"last\">";
                // line 44
                echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "exception"), "shortdesc"), $this->getContext($context, "exception")), "html", null, true));
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 47
            echo "        </table>
    ";
        }
    }

    public function getTemplateName()
    {
        return "pages/namespace.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 47,  141 => 44,  137 => 43,  130 => 41,  121 => 37,  148 => 46,  139 => 44,  135 => 43,  129 => 40,  126 => 39,  122 => 37,  113 => 35,  109 => 34,  105 => 32,  100 => 30,  83 => 25,  80 => 24,  71 => 19,  25 => 3,  118 => 35,  104 => 31,  96 => 28,  90 => 27,  87 => 26,  84 => 23,  75 => 20,  57 => 14,  46 => 10,  125 => 64,  120 => 61,  107 => 59,  22 => 4,  91 => 26,  72 => 21,  67 => 11,  61 => 16,  55 => 14,  95 => 27,  59 => 26,  43 => 9,  142 => 39,  134 => 42,  127 => 35,  123 => 33,  116 => 32,  112 => 30,  103 => 31,  99 => 30,  93 => 27,  86 => 50,  70 => 18,  64 => 17,  53 => 14,  24 => 3,  41 => 8,  39 => 7,  19 => 4,  377 => 58,  361 => 56,  356 => 55,  353 => 54,  348 => 53,  331 => 52,  329 => 51,  327 => 50,  316 => 49,  301 => 46,  289 => 45,  263 => 40,  259 => 39,  256 => 37,  253 => 35,  251 => 34,  234 => 33,  232 => 32,  220 => 31,  209 => 28,  203 => 27,  197 => 26,  183 => 25,  172 => 22,  166 => 21,  160 => 20,  146 => 19,  133 => 16,  131 => 41,  124 => 38,  117 => 35,  115 => 10,  102 => 32,  51 => 11,  40 => 6,  35 => 8,  32 => 4,  23 => 6,  17 => 1,  108 => 32,  101 => 30,  97 => 29,  88 => 25,  85 => 15,  81 => 23,  77 => 21,  62 => 16,  60 => 9,  56 => 15,  50 => 13,  47 => 11,  44 => 9,  38 => 6,  78 => 23,  74 => 20,  69 => 20,  58 => 15,  54 => 13,  48 => 10,  29 => 5,  92 => 26,  89 => 25,  82 => 8,  79 => 22,  73 => 20,  68 => 18,  66 => 18,  63 => 10,  45 => 9,  42 => 9,  36 => 5,  31 => 5,  20 => 8,  49 => 11,  37 => 7,  33 => 4,  30 => 6,  27 => 8,  26 => 3,);
    }
}
