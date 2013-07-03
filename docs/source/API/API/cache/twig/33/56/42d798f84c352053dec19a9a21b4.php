<?php

/* pages/namespace.twig */
class __TwigTemplate_335642d798f84c352053dec19a9a21b4 extends Twig_Template
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
        return $this->env->resolveTemplate((isset($context["page_layout"]) ? $context["page_layout"] : $this->getContext($context, "page_layout")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["__internal_7dbdad7ce6763710675b90519767a1804aefa14b"] = $this->env->loadTemplate("macros.twig");
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, (isset($context["namespace"]) ? $context["namespace"] : $this->getContext($context, "namespace")), "html", null, true);
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
        echo twig_escape_filter($this->env, (isset($context["namespace"]) ? $context["namespace"] : $this->getContext($context, "namespace")), "html", null, true);
        echo "</h1>
";
    }

    // line 14
    public function block_content($context, array $blocks = array())
    {
        // line 15
        echo "    ";
        if ((isset($context["classes"]) ? $context["classes"] : $this->getContext($context, "classes"))) {
            // line 16
            echo "        <table>
            ";
            // line 17
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["classes"]) ? $context["classes"] : $this->getContext($context, "classes")));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 18
                echo "                <tr>
                    <td>";
                // line 19
                echo $context["__internal_7dbdad7ce6763710675b90519767a1804aefa14b"]->getclass_link((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
                echo "</td>
                    <td class=\"last\">";
                // line 20
                echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "shortdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class"))), "html", null, true));
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 23
            echo "        </table>
    ";
        }
        // line 25
        echo "
    ";
        // line 26
        if ((isset($context["interfaces"]) ? $context["interfaces"] : $this->getContext($context, "interfaces"))) {
            // line 27
            echo "        <h2>Interfaces</h2>
        <table>
            ";
            // line 29
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["interfaces"]) ? $context["interfaces"] : $this->getContext($context, "interfaces")));
            foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                // line 30
                echo "                <tr>
                    <td>";
                // line 31
                echo $context["__internal_7dbdad7ce6763710675b90519767a1804aefa14b"]->getclass_link((isset($context["interface"]) ? $context["interface"] : $this->getContext($context, "interface")));
                echo "</td>
                    <td class=\"last\">";
                // line 32
                echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["interface"]) ? $context["interface"] : $this->getContext($context, "interface")), "shortdesc"), (isset($context["interface"]) ? $context["interface"] : $this->getContext($context, "interface"))), "html", null, true));
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 35
            echo "        </table>
    ";
        }
        // line 37
        echo "
    ";
        // line 38
        if ((isset($context["exceptions"]) ? $context["exceptions"] : $this->getContext($context, "exceptions"))) {
            // line 39
            echo "        <h2>Exceptions</h2>
        <table>
            ";
            // line 41
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["exceptions"]) ? $context["exceptions"] : $this->getContext($context, "exceptions")));
            foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
                // line 42
                echo "                <tr>
                    <td>";
                // line 43
                echo $context["__internal_7dbdad7ce6763710675b90519767a1804aefa14b"]->getclass_link((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")));
                echo "</td>
                    <td class=\"last\">";
                // line 44
                echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), "shortdesc"), (isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception"))), "html", null, true));
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
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
        return array (  152 => 47,  143 => 44,  139 => 43,  132 => 41,  123 => 37,  150 => 46,  141 => 44,  137 => 43,  131 => 40,  128 => 39,  124 => 37,  115 => 35,  111 => 34,  107 => 32,  102 => 30,  85 => 25,  82 => 24,  73 => 19,  27 => 3,  120 => 35,  106 => 31,  98 => 28,  92 => 27,  89 => 26,  86 => 23,  77 => 20,  59 => 14,  48 => 10,  127 => 64,  122 => 61,  109 => 59,  24 => 4,  93 => 26,  74 => 21,  69 => 11,  63 => 16,  57 => 14,  97 => 27,  61 => 26,  45 => 9,  144 => 39,  136 => 42,  129 => 35,  125 => 33,  118 => 32,  114 => 30,  105 => 31,  101 => 30,  95 => 27,  88 => 50,  72 => 18,  66 => 17,  55 => 14,  26 => 3,  43 => 8,  41 => 7,  21 => 4,  379 => 58,  363 => 56,  358 => 55,  355 => 54,  350 => 53,  333 => 52,  331 => 51,  329 => 50,  318 => 49,  303 => 46,  291 => 45,  265 => 40,  261 => 39,  258 => 37,  255 => 35,  253 => 34,  236 => 33,  234 => 32,  222 => 31,  211 => 28,  205 => 27,  199 => 26,  185 => 25,  174 => 22,  168 => 21,  162 => 20,  148 => 19,  135 => 16,  133 => 41,  126 => 38,  119 => 35,  117 => 10,  104 => 32,  53 => 11,  42 => 6,  37 => 8,  34 => 4,  25 => 6,  19 => 1,  110 => 32,  103 => 30,  99 => 29,  90 => 25,  87 => 15,  83 => 23,  79 => 21,  64 => 16,  62 => 9,  58 => 15,  52 => 13,  49 => 11,  46 => 9,  40 => 6,  80 => 23,  76 => 20,  71 => 20,  60 => 15,  56 => 13,  50 => 10,  31 => 5,  94 => 26,  91 => 25,  84 => 8,  81 => 22,  75 => 20,  70 => 18,  68 => 18,  65 => 10,  47 => 9,  44 => 9,  38 => 5,  33 => 5,  22 => 8,  51 => 11,  39 => 7,  35 => 4,  32 => 6,  29 => 8,  28 => 3,);
    }
}
