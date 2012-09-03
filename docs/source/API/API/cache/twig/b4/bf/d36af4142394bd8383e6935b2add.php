<?php

/* pages/class.twig */
class __TwigTemplate_b4bfd36af4142394bd8383e6935b2add extends Twig_Template
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
        return $this->env->resolveTemplate($this->getContext($context, "page_layout"));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"] = $this->env->loadTemplate("macros.twig");
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "class"), "name"), "html", null, true);
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
        if ($this->getAttribute($this->getContext($context, "class"), "interface")) {
            echo "Interface";
        } else {
            echo "Class";
        }
        echo "</div>
    <h1>";
        // line 11
        echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->getnamespace_link($this->getAttribute($this->getContext($context, "class"), "namespace"));
        if ($this->getAttribute($this->getContext($context, "class"), "namespace")) {
            echo "\\";
        }
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "class"), "shortname"), "html", null, true);
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
        if (($this->getAttribute($this->getContext($context, "class"), "shortdesc") || $this->getAttribute($this->getContext($context, "class"), "longdesc"))) {
            // line 18
            echo "        <div class=\"description\">
            <p>";
            // line 19
            echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "class"), "shortdesc"), $this->getContext($context, "class")), "html", null, true));
            echo "</p>
            <p>";
            // line 20
            echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "class"), "longdesc"), $this->getContext($context, "class")), "html", null, true));
            echo "</p>
        </div>
    ";
        }
        // line 23
        echo "
    ";
        // line 24
        if ($this->getContext($context, "constants")) {
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
        if ($this->getContext($context, "properties")) {
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
        if ($this->getContext($context, "methods")) {
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
        if (((!$this->getAttribute($this->getContext($context, "class"), "interface")) && $this->getAttribute($this->getContext($context, "class"), "abstract"))) {
            echo "abstract ";
        }
        // line 49
        if ($this->getAttribute($this->getContext($context, "class"), "interface")) {
            echo "interface";
        } else {
            echo "class";
        }
        // line 50
        echo "    <strong>";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "class"), "shortname"), "html", null, true);
        echo "</strong>";
        // line 51
        if ($this->getAttribute($this->getContext($context, "class"), "parent")) {
            // line 52
            echo "        extends ";
            echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->getclass_link($this->getAttribute($this->getContext($context, "class"), "parent"));
        }
        // line 54
        if ((twig_length_filter($this->env, $this->getAttribute($this->getContext($context, "class"), "interfaces")) > 0)) {
            // line 55
            echo "        implements
        ";
            // line 56
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "class"), "interfaces"));
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
                echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->getclass_link($this->getContext($context, "interface"));
                // line 58
                if ((!$this->getAttribute($this->getContext($context, "loop"), "last"))) {
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
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
        }
    }

    // line 63
    public function block_method_signature($context, array $blocks = array())
    {
        // line 64
        if ($this->getAttribute($this->getContext($context, "method"), "final")) {
            echo "final";
        }
        // line 65
        echo "    ";
        if ($this->getAttribute($this->getContext($context, "method"), "abstract")) {
            echo "abstract";
        }
        // line 66
        echo "    ";
        if ($this->getAttribute($this->getContext($context, "method"), "static")) {
            echo "static";
        }
        // line 67
        echo "    ";
        if ($this->getAttribute($this->getContext($context, "method"), "public")) {
            echo "public";
        }
        // line 68
        echo "    ";
        if ($this->getAttribute($this->getContext($context, "method"), "protected")) {
            echo "protected";
        }
        // line 69
        echo "    ";
        if ($this->getAttribute($this->getContext($context, "method"), "private")) {
            echo "private";
        }
        // line 70
        echo "    ";
        echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->gethint_link($this->getAttribute($this->getContext($context, "method"), "hint"));
        echo "
    <strong>";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "method"), "name"), "html", null, true);
        echo "</strong>";
        $this->displayBlock("method_parameters_signature", $context, $blocks);
    }

    // line 74
    public function block_method_parameters_signature($context, array $blocks = array())
    {
        // line 75
        $context["__internal_aae0b0eefba5bd21532253b760fb67a75bc0c177"] = $this->env->loadTemplate("macros.twig");
        // line 76
        echo $context["__internal_aae0b0eefba5bd21532253b760fb67a75bc0c177"]->getmethod_parameters_signature($this->getContext($context, "method"));
    }

    // line 79
    public function block_parameters($context, array $blocks = array())
    {
        // line 80
        echo "    <table>
        ";
        // line 81
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "method"), "parameters"));
        foreach ($context['_seq'] as $context["_key"] => $context["parameter"]) {
            // line 82
            echo "            <tr>
                <td>";
            // line 83
            if ($this->getAttribute($this->getContext($context, "parameter"), "hint")) {
                echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->gethint_link($this->getAttribute($this->getContext($context, "parameter"), "hint"));
            }
            echo "</td>
                <td>\$";
            // line 84
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "parameter"), "name"), "html", null, true);
            echo "</td>
                <td>";
            // line 85
            echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "parameter"), "shortdesc"), $this->getContext($context, "class")), "html", null, true));
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parameter'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
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
        echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->gethint_link($this->getAttribute($this->getContext($context, "method"), "hint"));
        echo "</td>
            <td>";
        // line 95
        echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "method"), "hintDesc"), $this->getContext($context, "class")), "html", null, true));
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
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "method"), "exceptions"));
        foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
            // line 103
            echo "            <tr>
                <td>";
            // line 104
            echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->getclass_link($this->getAttribute($this->getContext($context, "exception"), 0, array(), "array"));
            echo "</td>
                <td>";
            // line 105
            echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "exception"), 1, array(), "array"), $this->getContext($context, "class")), "html", null, true));
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
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
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "method"), "tags", array(0 => "see"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
            // line 114
            echo "            <tr>
                <td>";
            // line 115
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "tag"), 0, array(), "array"), "html", null, true);
            echo "</td>
                <td>";
            // line 116
            echo twig_escape_filter($this->env, twig_join_filter(twig_slice($this->env, $this->getContext($context, "tag"), 1, null), " "), "html", null, true);
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
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
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "constants"));
        foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
            // line 125
            echo "            <tr>
                <td>";
            // line 126
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "constant"), "name"), "html", null, true);
            echo "</td>
                <td class=\"last\">
                    <p><em>";
            // line 128
            echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "constant"), "shortdesc"), $this->getContext($context, "class")), "html", null, true));
            echo "</em></p>
                    <p>";
            // line 129
            echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "constant"), "longdesc"), $this->getContext($context, "class")), "html", null, true));
            echo "</p>
                </td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['constant'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
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
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "properties"));
        foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
            // line 139
            echo "            <tr>
                <td class=\"type\" id=\"property_";
            // line 140
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "property"), "name"), "html", null, true);
            echo "\">
                    ";
            // line 141
            if ($this->getAttribute($this->getContext($context, "property"), "static")) {
                echo "static";
            }
            // line 142
            echo "                    ";
            if ($this->getAttribute($this->getContext($context, "property"), "protected")) {
                echo "protected";
            }
            // line 143
            echo "                    ";
            echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->gethint_link($this->getAttribute($this->getContext($context, "property"), "hint"));
            echo "
                </td>
                <td>\$";
            // line 145
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "property"), "name"), "html", null, true);
            echo "</td>
                <td class=\"last\">";
            // line 146
            echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "property"), "shortdesc"), $this->getContext($context, "class")), "html", null, true));
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['property'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
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
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "methods"));
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
            if ($this->getAttribute($this->getContext($context, "method"), "static")) {
                echo "static&nbsp;";
            }
            echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->gethint_link($this->getAttribute($this->getContext($context, "method"), "hint"));
            echo "
                </td>
                <td class=\"last\">
                    <a href=\"#method_";
            // line 160
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "method"), "name"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "method"), "name"), "html", null, true);
            echo "</a>";
            $this->displayBlock("method_parameters_signature", $context, $blocks);
            echo "
                    <p>";
            // line 161
            echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "method"), "shortdesc"), $this->getContext($context, "class")), "html", null, true));
            echo "</p>
                </td>
                <td>";
            // line 164
            if (($this->getAttribute($this->getContext($context, "method"), "class") != $this->getContext($context, "class"))) {
                // line 165
                echo "<small>from&nbsp;";
                echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->getmethod_link($this->getContext($context, "method"), array(), false, true);
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
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
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
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "methods"));
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
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    // line 179
    public function block_method($context, array $blocks = array())
    {
        // line 180
        echo "    <h3 id=\"method_";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "method"), "name"), "html", null, true);
        echo "\">
        <div class=\"location\">";
        // line 181
        if (($this->getAttribute($this->getContext($context, "method"), "class") != $this->getContext($context, "class"))) {
            echo "in ";
            echo $context["__internal_f5bb4fd7271e552b84dd4e0f323dc1a92792b6f5"]->getmethod_link($this->getContext($context, "method"), array(), false, true);
            echo " ";
        }
        echo "at line ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "method"), "line"), "html", null, true);
        echo "</div>
        <code>";
        // line 182
        $this->displayBlock("method_signature", $context, $blocks);
        echo "</code>
    </h3>
    <div class=\"details\">
        <p>";
        // line 185
        echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "method"), "shortdesc"), $this->getContext($context, "class")), "html", null, true));
        echo "</p>
        <p>";
        // line 186
        echo nl2br(twig_escape_filter($this->env, $this->env->getExtension('sami')->parseDesc($context, $this->getAttribute($this->getContext($context, "method"), "longdesc"), $this->getContext($context, "class")), "html", null, true));
        echo "</p>
        <div class=\"tags\">
            ";
        // line 188
        if ($this->getAttribute($this->getContext($context, "method"), "parameters")) {
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
        if (($this->getAttribute($this->getContext($context, "method"), "hintDesc") || $this->getAttribute($this->getContext($context, "method"), "hint"))) {
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
        if ($this->getAttribute($this->getContext($context, "method"), "exceptions")) {
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
        if ($this->getAttribute($this->getContext($context, "method"), "tags", array(0 => "see"), "method")) {
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
        return array (  678 => 211,  673 => 209,  669 => 207,  667 => 206,  664 => 205,  659 => 203,  655 => 201,  653 => 200,  650 => 199,  645 => 197,  641 => 195,  639 => 194,  636 => 193,  631 => 191,  627 => 189,  625 => 188,  620 => 186,  616 => 185,  610 => 182,  600 => 181,  595 => 180,  592 => 179,  573 => 175,  555 => 174,  552 => 173,  547 => 170,  531 => 167,  526 => 165,  524 => 164,  519 => 161,  511 => 160,  502 => 157,  498 => 155,  481 => 154,  478 => 153,  475 => 152,  470 => 149,  461 => 146,  457 => 145,  451 => 143,  446 => 142,  442 => 141,  438 => 140,  435 => 139,  431 => 138,  428 => 137,  425 => 136,  420 => 133,  410 => 129,  406 => 128,  401 => 126,  398 => 125,  394 => 124,  391 => 123,  388 => 122,  383 => 119,  374 => 116,  370 => 115,  367 => 114,  363 => 113,  360 => 112,  357 => 111,  352 => 108,  343 => 105,  339 => 104,  336 => 103,  332 => 102,  326 => 100,  318 => 95,  314 => 94,  310 => 92,  307 => 91,  302 => 88,  293 => 85,  283 => 83,  280 => 82,  276 => 81,  273 => 80,  270 => 79,  266 => 76,  264 => 75,  261 => 74,  255 => 71,  250 => 70,  245 => 69,  240 => 68,  235 => 67,  230 => 66,  225 => 65,  221 => 64,  218 => 63,  199 => 58,  180 => 56,  177 => 55,  175 => 54,  171 => 52,  169 => 51,  165 => 50,  159 => 49,  155 => 48,  152 => 47,  145 => 43,  138 => 39,  132 => 36,  110 => 27,  106 => 25,  150 => 47,  141 => 44,  137 => 43,  130 => 41,  121 => 37,  148 => 46,  139 => 44,  135 => 43,  129 => 35,  126 => 39,  122 => 37,  113 => 35,  109 => 34,  105 => 32,  100 => 30,  83 => 25,  80 => 15,  71 => 19,  25 => 3,  118 => 30,  104 => 24,  96 => 28,  90 => 27,  87 => 26,  84 => 23,  75 => 20,  57 => 14,  46 => 10,  125 => 64,  120 => 31,  107 => 59,  22 => 4,  91 => 19,  72 => 21,  67 => 11,  61 => 16,  55 => 9,  95 => 20,  59 => 26,  43 => 9,  142 => 39,  134 => 37,  127 => 35,  123 => 33,  116 => 32,  112 => 30,  103 => 31,  99 => 30,  93 => 27,  86 => 17,  70 => 18,  64 => 17,  53 => 14,  24 => 3,  41 => 5,  39 => 7,  19 => 4,  377 => 58,  361 => 56,  356 => 55,  353 => 54,  348 => 53,  331 => 52,  329 => 101,  327 => 50,  316 => 49,  301 => 46,  289 => 84,  263 => 40,  259 => 39,  256 => 37,  253 => 35,  251 => 34,  234 => 33,  232 => 32,  220 => 31,  209 => 28,  203 => 27,  197 => 57,  183 => 25,  172 => 22,  166 => 21,  160 => 20,  146 => 19,  133 => 16,  131 => 41,  124 => 33,  117 => 35,  115 => 29,  102 => 32,  51 => 11,  40 => 6,  35 => 8,  32 => 4,  23 => 6,  17 => 1,  108 => 32,  101 => 23,  97 => 29,  88 => 18,  85 => 15,  81 => 23,  77 => 14,  62 => 16,  60 => 9,  56 => 15,  50 => 13,  47 => 11,  44 => 9,  38 => 6,  78 => 23,  74 => 20,  69 => 20,  58 => 10,  54 => 13,  48 => 10,  29 => 5,  92 => 26,  89 => 25,  82 => 8,  79 => 22,  73 => 20,  68 => 18,  66 => 18,  63 => 10,  45 => 9,  42 => 9,  36 => 3,  31 => 5,  20 => 8,  49 => 7,  37 => 7,  33 => 4,  30 => 6,  27 => 8,  26 => 3,);
    }
}
