<?php

/* pages/class.twig */
class __TwigTemplate_1647bd6f794a82b95acfde6a6f2048778716621815a792b5ff8c1405a108e8b4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'body_class' => array($this, 'block_body_class'),
            'content_header' => array($this, 'block_content_header'),
            'content' => array($this, 'block_content'),
            'class_signature' => array($this, 'block_class_signature'),
            'method_signature' => array($this, 'block_method_signature'),
            'method_parameters_signature' => array($this, 'block_method_parameters_signature'),
            'parameters' => array($this, 'block_parameters'),
            'return' => array($this, 'block_return'),
            'exceptions' => array($this, 'block_exceptions'),
            'see' => array($this, 'block_see'),
            'constants' => array($this, 'block_constants'),
            'properties' => array($this, 'block_properties'),
            'methods' => array($this, 'block_methods'),
            'methods_details' => array($this, 'block_methods_details'),
            'method' => array($this, 'block_method'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate((isset($context["page_layout"]) ? $context["page_layout"] : $this->getContext($context, "page_layout")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"] = $this->env->loadTemplate("macros.twig");
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "name"), "html", null, true);
        echo " | ";
        $this->displayParentBlock("title", $context, $blocks);
    }

    // line 7
    public function block_body_class($context, array $blocks = array())
    {
        echo "class";
    }

    // line 9
    public function block_content_header($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"type\">";
        echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "categoryName")), "html", null, true);
        echo "</div>
    <h1>";
        // line 11
        echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->getnamespace_link($this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "namespace"));
        if ($this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "namespace")) {
            echo "\\";
        }
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "shortname"), "html", null, true);
        echo "</h1>
";
    }

    // line 14
    public function block_content($context, array $blocks = array())
    {
        // line 15
        echo "    <p>";
        $this->displayBlock("class_signature", $context, $blocks);
        echo "</p>

    ";
        // line 17
        if (($this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "shortdesc") || $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "longdesc"))) {
            // line 18
            echo "        <div class=\"description\">
            <p>";
            // line 19
            echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "shortdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
            echo "</p>
            <p>";
            // line 20
            echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "longdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
            echo "</p>
        </div>
    ";
        }
        // line 23
        echo "
    ";
        // line 24
        if ((isset($context["constants"]) ? $context["constants"] : $this->getContext($context, "constants"))) {
            // line 25
            echo "        <h2>Constants</h2>

        ";
            // line 27
            $this->displayBlock("constants", $context, $blocks);
            echo "
    ";
        }
        // line 29
        echo "
    ";
        // line 30
        if ((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties"))) {
            // line 31
            echo "        <h2>Properties</h2>

        ";
            // line 33
            $this->displayBlock("properties", $context, $blocks);
            echo "
    ";
        }
        // line 35
        echo "
    ";
        // line 36
        if ((isset($context["methods"]) ? $context["methods"] : $this->getContext($context, "methods"))) {
            // line 37
            echo "        <h2>Methods</h2>

        ";
            // line 39
            $this->displayBlock("methods", $context, $blocks);
            echo "

        <h2>Details</h2>

        ";
            // line 43
            $this->displayBlock("methods_details", $context, $blocks);
            echo "
    ";
        }
    }

    // line 47
    public function block_class_signature($context, array $blocks = array())
    {
        // line 48
        if (((!$this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "interface")) && $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "abstract"))) {
            echo "abstract ";
        }
        // line 49
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "categoryName"), "html", null, true);
        echo "
    <strong>";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "shortname"), "html", null, true);
        echo "</strong>";
        // line 51
        if ($this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "parent")) {
            // line 52
            echo "        extends ";
            echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->getclass_link($this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "parent"));
        }
        // line 54
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "interfaces")) > 0)) {
            // line 55
            echo "        implements
        ";
            // line 56
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")), "interfaces"));
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
            foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                // line 57
                echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->getclass_link((isset($context["interface"]) ? $context["interface"] : $this->getContext($context, "interface")));
                // line 58
                if ((!$this->getAttribute((isset($context["loop"]) ? $context["loop"] : $this->getContext($context, "loop")), "last"))) {
                    echo ", ";
                }
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
    }

    // line 63
    public function block_method_signature($context, array $blocks = array())
    {
        // line 64
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "final")) {
            echo "final";
        }
        // line 65
        echo "    ";
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "abstract")) {
            echo "abstract";
        }
        // line 66
        echo "    ";
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "static")) {
            echo "static";
        }
        // line 67
        echo "    ";
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "public")) {
            echo "public";
        }
        // line 68
        echo "    ";
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "protected")) {
            echo "protected";
        }
        // line 69
        echo "    ";
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "private")) {
            echo "private";
        }
        // line 70
        echo "    ";
        echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->gethint_link($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "hint"));
        echo "
    <strong>";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "name"), "html", null, true);
        echo "</strong>";
        $this->displayBlock("method_parameters_signature", $context, $blocks);
    }

    // line 74
    public function block_method_parameters_signature($context, array $blocks = array())
    {
        // line 75
        $context["__internal_b6dd97135bdfcf971384bac328f4d3a6b409eda393fdc34a5fb6a2d794772643"] = $this->env->loadTemplate("macros.twig");
        // line 76
        echo $context["__internal_b6dd97135bdfcf971384bac328f4d3a6b409eda393fdc34a5fb6a2d794772643"]->getmethod_parameters_signature((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")));
    }

    // line 79
    public function block_parameters($context, array $blocks = array())
    {
        // line 80
        echo "    <table>
        ";
        // line 81
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "parameters"));
        foreach ($context['_seq'] as $context["_key"] => $context["parameter"]) {
            // line 82
            echo "            <tr>
                <td>";
            // line 83
            if ($this->getAttribute((isset($context["parameter"]) ? $context["parameter"] : $this->getContext($context, "parameter")), "hint")) {
                echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->gethint_link($this->getAttribute((isset($context["parameter"]) ? $context["parameter"] : $this->getContext($context, "parameter")), "hint"));
            }
            echo "</td>
                <td>\$";
            // line 84
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["parameter"]) ? $context["parameter"] : $this->getContext($context, "parameter")), "name"), "html", null, true);
            echo "</td>
                <td>";
            // line 85
            echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["parameter"]) ? $context["parameter"] : $this->getContext($context, "parameter")), "shortdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parameter'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 88
        echo "    </table>
";
    }

    // line 91
    public function block_return($context, array $blocks = array())
    {
        // line 92
        echo "    <table>
        <tr>
            <td>";
        // line 94
        echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->gethint_link($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "hint"));
        echo "</td>
            <td>";
        // line 95
        echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "hintDesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
        echo "</td>
        </tr>
    </table>
";
    }

    // line 100
    public function block_exceptions($context, array $blocks = array())
    {
        // line 101
        echo "    <table>
        ";
        // line 102
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "exceptions"));
        foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
            // line 103
            echo "            <tr>
                <td>";
            // line 104
            echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->getclass_link($this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), 0, array(), "array"));
            echo "</td>
                <td>";
            // line 105
            echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["exception"]) ? $context["exception"] : $this->getContext($context, "exception")), 1, array(), "array"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 108
        echo "    </table>
";
    }

    // line 111
    public function block_see($context, array $blocks = array())
    {
        // line 112
        echo "    <table>
        ";
        // line 113
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "tags", array(0 => "see"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
            // line 114
            echo "            <tr>
                <td>";
            // line 115
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["tag"]) ? $context["tag"] : $this->getContext($context, "tag")), 0, array(), "array"), "html", null, true);
            echo "</td>
                <td>";
            // line 116
            echo twig_escape_filter($this->env, twig_join_filter(twig_slice($this->env, (isset($context["tag"]) ? $context["tag"] : $this->getContext($context, "tag")), 1, null), " "), "html", null, true);
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 119
        echo "    </table>
";
    }

    // line 122
    public function block_constants($context, array $blocks = array())
    {
        // line 123
        echo "    <table>
        ";
        // line 124
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["constants"]) ? $context["constants"] : $this->getContext($context, "constants")));
        foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
            // line 125
            echo "            <tr>
                <td>";
            // line 126
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["constant"]) ? $context["constant"] : $this->getContext($context, "constant")), "name"), "html", null, true);
            echo "</td>
                <td class=\"last\">
                    <p><em>";
            // line 128
            echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["constant"]) ? $context["constant"] : $this->getContext($context, "constant")), "shortdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
            echo "</em></p>
                    <p>";
            // line 129
            echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["constant"]) ? $context["constant"] : $this->getContext($context, "constant")), "longdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
            echo "</p>
                </td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 133
        echo "    </table>
";
    }

    // line 136
    public function block_properties($context, array $blocks = array())
    {
        // line 137
        echo "    <table>
        ";
        // line 138
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")));
        foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
            // line 139
            echo "            <tr>
                <td class=\"type\" id=\"property_";
            // line 140
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["property"]) ? $context["property"] : $this->getContext($context, "property")), "name"), "html", null, true);
            echo "\">
                    ";
            // line 141
            if ($this->getAttribute((isset($context["property"]) ? $context["property"] : $this->getContext($context, "property")), "static")) {
                echo "static";
            }
            // line 142
            echo "                    ";
            if ($this->getAttribute((isset($context["property"]) ? $context["property"] : $this->getContext($context, "property")), "protected")) {
                echo "protected";
            }
            // line 143
            echo "                    ";
            echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->gethint_link($this->getAttribute((isset($context["property"]) ? $context["property"] : $this->getContext($context, "property")), "hint"));
            echo "
                </td>
                <td>\$";
            // line 145
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["property"]) ? $context["property"] : $this->getContext($context, "property")), "name"), "html", null, true);
            echo "</td>
                <td class=\"last\">";
            // line 146
            echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["property"]) ? $context["property"] : $this->getContext($context, "property")), "shortdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 149
        echo "    </table>
";
    }

    // line 152
    public function block_methods($context, array $blocks = array())
    {
        // line 153
        echo "    <table>
        ";
        // line 154
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["methods"]) ? $context["methods"] : $this->getContext($context, "methods")));
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
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            // line 155
            echo "            <tr>
                <td class=\"type\">
                    ";
            // line 157
            if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "static")) {
                echo "static&nbsp;";
            }
            echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->gethint_link($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "hint"));
            echo "
                </td>
                <td class=\"last\">
                    <a href=\"#method_";
            // line 160
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "name"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "name"), "html", null, true);
            echo "</a>";
            $this->displayBlock("method_parameters_signature", $context, $blocks);
            echo "
                    <p>";
            // line 161
            echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "shortdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
            echo "</p>
                </td>
                <td>";
            // line 164
            if ((!($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "class") === (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class"))))) {
                // line 165
                echo "<small>from&nbsp;";
                echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->getmethod_link((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), array(), false, true);
                echo "</small>";
            }
            // line 167
            echo "</td>
            </tr>
        ";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 170
        echo "    </table>
";
    }

    // line 173
    public function block_methods_details($context, array $blocks = array())
    {
        // line 174
        echo "    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["methods"]) ? $context["methods"] : $this->getContext($context, "methods")));
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
        foreach ($context['_seq'] as $context["_key"] => $context["method"]) {
            // line 175
            echo "        ";
            $this->displayBlock("method", $context, $blocks);
            echo "
    ";
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
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['method'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    // line 179
    public function block_method($context, array $blocks = array())
    {
        // line 180
        echo "    <h3 id=\"method_";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "name"), "html", null, true);
        echo "\">
        <div class=\"location\">";
        // line 181
        if ((!($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "class") === (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class"))))) {
            echo "in ";
            echo $context["__internal_ff448dc76c96a2d01e060554289df0582b3ec87ccb3c8ff255280c9bf865bbc7"]->getmethod_link((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), array(), false, true);
            echo " ";
        }
        echo "at line ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "line"), "html", null, true);
        echo "</div>
        <code>";
        // line 182
        $this->displayBlock("method_signature", $context, $blocks);
        echo "</code>
    </h3>
    <div class=\"details\">
        <p>";
        // line 185
        echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "shortdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
        echo "</p>
        <p>";
        // line 186
        echo $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "longdesc"), (isset($context["class"]) ? $context["class"] : $this->getContext($context, "class")));
        echo "</p>
        <div class=\"tags\">
            ";
        // line 188
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "parameters")) {
            // line 189
            echo "                <h4>Parameters</h4>

                ";
            // line 191
            $this->displayBlock("parameters", $context, $blocks);
            echo "
            ";
        }
        // line 193
        echo "
            ";
        // line 194
        if (($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "hintDesc") || $this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "hint"))) {
            // line 195
            echo "                <h4>Return Value</h4>

                ";
            // line 197
            $this->displayBlock("return", $context, $blocks);
            echo "
            ";
        }
        // line 199
        echo "
            ";
        // line 200
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "exceptions")) {
            // line 201
            echo "                <h4>Exceptions</h4>

                ";
            // line 203
            $this->displayBlock("exceptions", $context, $blocks);
            echo "
            ";
        }
        // line 205
        echo "
            ";
        // line 206
        if ($this->getAttribute((isset($context["method"]) ? $context["method"] : $this->getContext($context, "method")), "tags", array(0 => "see"), "method")) {
            // line 207
            echo "                <h4>See also</h4>

                ";
            // line 209
            $this->displayBlock("see", $context, $blocks);
            echo "
            ";
        }
        // line 211
        echo "        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "pages/class.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  674 => 211,  669 => 209,  665 => 207,  663 => 206,  660 => 205,  655 => 203,  651 => 201,  649 => 200,  646 => 199,  641 => 197,  637 => 195,  635 => 194,  632 => 193,  627 => 191,  623 => 189,  621 => 188,  616 => 186,  612 => 185,  606 => 182,  596 => 181,  591 => 180,  588 => 179,  569 => 175,  551 => 174,  548 => 173,  543 => 170,  527 => 167,  522 => 165,  520 => 164,  515 => 161,  507 => 160,  498 => 157,  494 => 155,  477 => 154,  474 => 153,  471 => 152,  466 => 149,  457 => 146,  453 => 145,  447 => 143,  442 => 142,  438 => 141,  434 => 140,  431 => 139,  427 => 138,  424 => 137,  421 => 136,  416 => 133,  406 => 129,  402 => 128,  397 => 126,  394 => 125,  390 => 124,  387 => 123,  384 => 122,  370 => 116,  366 => 115,  359 => 113,  356 => 112,  353 => 111,  348 => 108,  339 => 105,  335 => 104,  332 => 103,  328 => 102,  325 => 101,  322 => 100,  314 => 95,  310 => 94,  306 => 92,  298 => 88,  289 => 85,  285 => 84,  279 => 83,  276 => 82,  272 => 81,  269 => 80,  266 => 79,  262 => 76,  260 => 75,  257 => 74,  251 => 71,  246 => 70,  241 => 69,  231 => 67,  226 => 66,  221 => 65,  217 => 64,  214 => 63,  195 => 58,  193 => 57,  176 => 56,  173 => 55,  171 => 54,  167 => 52,  165 => 51,  157 => 49,  153 => 48,  130 => 36,  116 => 30,  113 => 29,  108 => 27,  78 => 15,  152 => 47,  143 => 43,  139 => 43,  132 => 37,  123 => 37,  39 => 7,  150 => 47,  141 => 44,  137 => 43,  131 => 40,  128 => 39,  124 => 37,  115 => 35,  111 => 34,  107 => 32,  102 => 24,  73 => 19,  27 => 3,  120 => 35,  106 => 31,  89 => 19,  86 => 18,  77 => 20,  59 => 14,  127 => 35,  122 => 33,  109 => 59,  24 => 4,  82 => 24,  93 => 20,  69 => 11,  63 => 16,  57 => 9,  32 => 6,  98 => 28,  92 => 27,  85 => 25,  74 => 21,  61 => 26,  45 => 9,  144 => 39,  136 => 39,  129 => 35,  125 => 33,  118 => 31,  114 => 30,  105 => 31,  101 => 30,  95 => 27,  88 => 50,  72 => 18,  66 => 17,  55 => 14,  26 => 3,  43 => 5,  41 => 7,  21 => 4,  379 => 119,  363 => 114,  358 => 55,  355 => 54,  350 => 53,  333 => 52,  331 => 51,  329 => 50,  318 => 49,  303 => 91,  291 => 45,  265 => 40,  261 => 39,  258 => 37,  255 => 35,  253 => 34,  236 => 68,  234 => 32,  222 => 31,  211 => 28,  205 => 27,  199 => 26,  185 => 25,  174 => 22,  168 => 21,  162 => 50,  148 => 19,  135 => 16,  133 => 41,  126 => 38,  119 => 35,  117 => 10,  104 => 25,  53 => 11,  37 => 8,  34 => 4,  25 => 6,  19 => 1,  110 => 32,  103 => 30,  99 => 23,  90 => 25,  87 => 15,  83 => 23,  79 => 21,  64 => 16,  62 => 9,  52 => 13,  49 => 11,  46 => 9,  40 => 6,  80 => 23,  76 => 22,  71 => 20,  60 => 10,  56 => 13,  50 => 10,  31 => 5,  94 => 28,  91 => 25,  84 => 17,  81 => 7,  75 => 14,  68 => 18,  65 => 11,  47 => 9,  44 => 9,  35 => 4,  29 => 8,  22 => 8,  70 => 18,  58 => 15,  54 => 11,  51 => 7,  48 => 10,  42 => 6,  38 => 3,  33 => 5,  30 => 3,  28 => 3,);
    }
}
