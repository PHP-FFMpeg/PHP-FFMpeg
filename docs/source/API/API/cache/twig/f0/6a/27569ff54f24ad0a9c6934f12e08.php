<?php

/* namespace.twig */
class __TwigTemplate_f06a27569ff54f24ad0a9c6934f12e08 extends Twig_Template
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
        $context["__internal_ed1e7deba4dba5645d595dd1b5eaa0811d147cfd"] = $this->env->loadTemplate("macros.twig");
        $this->parent->display($context, array_merge($this->blocks, $blocks));
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
        echo "frame";
    }

    // line 9
    public function block_header($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"header\">
        <h1>";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "config", array(0 => "title"), "method"), "html", null, true);
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
        echo $context["__internal_ed1e7deba4dba5645d595dd1b5eaa0811d147cfd"]->getnamespace_link((isset($context["namespace"]) ? $context["namespace"] : $this->getContext($context, "namespace")), array("target" => "main"));
        echo "</h1>

    ";
        // line 23
        if ((isset($context["classes"]) ? $context["classes"] : $this->getContext($context, "classes"))) {
            // line 24
            echo "        <ul>
            ";
            // line 25
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["classes"]) ? $context["classes"] : $this->getContext($context, "classes")));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 26
                echo "                <li>";
                echo $context["__internal_ed1e7deba4dba5645d595dd1b5eaa0811d147cfd"]->getclass_link((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), array("target" => "main"));
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 28
            echo "        </ul>
    ";
        }
        // line 30
        echo "
    ";
        // line 31
        if ((isset($context["interfaces"]) ? $context["interfaces"] : $this->getContext($context, "interfaces"))) {
            // line 32
            echo "        <h2>Interfaces</h2>
        <ul>
            ";
            // line 34
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["interfaces"]) ? $context["interfaces"] : $this->getContext($context, "interfaces")));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 35
                echo "                <li>";
                echo $context["__internal_ed1e7deba4dba5645d595dd1b5eaa0811d147cfd"]->getclass_link((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), array("target" => "main"));
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 37
            echo "        </ul>
    ";
        }
        // line 39
        echo "
    ";
        // line 40
        if ((isset($context["exceptions"]) ? $context["exceptions"] : $this->getContext($context, "exceptions"))) {
            // line 41
            echo "        <h2>Exceptions</h2>
        <ul>
            ";
            // line 43
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["exceptions"]) ? $context["exceptions"] : $this->getContext($context, "exceptions")));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 44
                echo "                <li>";
                echo $context["__internal_ed1e7deba4dba5645d595dd1b5eaa0811d147cfd"]->getclass_link((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), array("target" => "main"));
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
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
        return array (  150 => 46,  141 => 44,  137 => 43,  131 => 40,  128 => 39,  124 => 37,  115 => 35,  111 => 34,  107 => 32,  102 => 30,  85 => 25,  82 => 24,  73 => 13,  27 => 3,  120 => 35,  106 => 33,  98 => 28,  92 => 27,  89 => 26,  86 => 25,  77 => 21,  59 => 14,  48 => 10,  127 => 64,  122 => 61,  109 => 59,  24 => 4,  93 => 27,  74 => 21,  69 => 11,  63 => 15,  57 => 7,  97 => 27,  61 => 26,  45 => 7,  144 => 39,  136 => 36,  129 => 35,  125 => 33,  118 => 32,  114 => 30,  105 => 31,  101 => 30,  95 => 28,  88 => 50,  72 => 18,  66 => 18,  55 => 14,  26 => 3,  43 => 8,  41 => 7,  21 => 4,  379 => 58,  363 => 56,  358 => 55,  355 => 54,  350 => 53,  333 => 52,  331 => 51,  329 => 50,  318 => 49,  303 => 46,  291 => 45,  265 => 40,  261 => 39,  258 => 37,  255 => 35,  253 => 34,  236 => 33,  234 => 32,  222 => 31,  211 => 28,  205 => 27,  199 => 26,  185 => 25,  174 => 22,  168 => 21,  162 => 20,  148 => 19,  135 => 16,  133 => 41,  126 => 13,  119 => 11,  117 => 10,  104 => 32,  53 => 11,  42 => 6,  37 => 8,  34 => 4,  25 => 6,  19 => 1,  110 => 32,  103 => 28,  99 => 55,  90 => 27,  87 => 15,  83 => 23,  79 => 21,  64 => 16,  62 => 9,  58 => 15,  52 => 13,  49 => 11,  46 => 9,  40 => 6,  80 => 23,  76 => 20,  71 => 20,  60 => 13,  56 => 13,  50 => 10,  31 => 5,  94 => 26,  91 => 25,  84 => 8,  81 => 22,  75 => 20,  70 => 19,  68 => 18,  65 => 10,  47 => 9,  44 => 9,  38 => 5,  33 => 5,  22 => 8,  51 => 12,  39 => 6,  35 => 4,  32 => 6,  29 => 8,  28 => 3,);
    }
}
