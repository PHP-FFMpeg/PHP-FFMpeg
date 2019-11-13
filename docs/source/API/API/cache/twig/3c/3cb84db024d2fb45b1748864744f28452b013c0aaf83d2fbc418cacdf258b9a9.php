<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* pages/class.twig */
class __TwigTemplate_eb4f94b2ce6012df5bbedea8f81b9bff6d4fecb64d9147504158c70bb51a5cca extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'body_class' => [$this, 'block_body_class'],
            'content_header' => [$this, 'block_content_header'],
            'content' => [$this, 'block_content'],
            'class_signature' => [$this, 'block_class_signature'],
            'method_signature' => [$this, 'block_method_signature'],
            'method_parameters_signature' => [$this, 'block_method_parameters_signature'],
            'parameters' => [$this, 'block_parameters'],
            'return' => [$this, 'block_return'],
            'exceptions' => [$this, 'block_exceptions'],
            'see' => [$this, 'block_see'],
            'constants' => [$this, 'block_constants'],
            'properties' => [$this, 'block_properties'],
            'methods' => [$this, 'block_methods'],
            'methods_details' => [$this, 'block_methods_details'],
            'method' => [$this, 'block_method'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return $this->loadTemplate(($context["page_layout"] ?? $this->getContext($context, "page_layout")), "pages/class.twig", 1);
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 3
        $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"] = $this->loadTemplate("macros.twig", "pages/class.twig", 3)->unwrap();
        // line 1
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        echo twig_escape_filter($this->env, $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "name", []), "html", null, true);
        echo " | ";
        $this->displayParentBlock("title", $context, $blocks);
    }

    // line 7
    public function block_body_class($context, array $blocks = [])
    {
        echo "class";
    }

    // line 9
    public function block_content_header($context, array $blocks = [])
    {
        // line 10
        echo "    <div class=\"type\">";
        echo twig_escape_filter($this->env, twig_capitalize_string_filter($this->env, $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "categoryName", [])), "html", null, true);
        echo "</div>
    <h1>";
        // line 11
        echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->getnamespace_link($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "namespace", []));
        if ($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "namespace", [])) {
            echo "\\";
        }
        echo twig_escape_filter($this->env, $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "shortname", []), "html", null, true);
        echo "</h1>
";
    }

    // line 14
    public function block_content($context, array $blocks = [])
    {
        // line 15
        echo "    <p>";
        $this->displayBlock("class_signature", $context, $blocks);
        echo "</p>

    ";
        // line 17
        if (($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "shortdesc", []) || $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "longdesc", []))) {
            // line 18
            echo "        <div class=\"description\">
            <p>";
            // line 19
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "shortdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
            echo "</p>
            <p>";
            // line 20
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "longdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
            echo "</p>
        </div>
    ";
        }
        // line 23
        echo "
    ";
        // line 24
        if (($context["constants"] ?? $this->getContext($context, "constants"))) {
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
        if (($context["properties"] ?? $this->getContext($context, "properties"))) {
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
        if (($context["methods"] ?? $this->getContext($context, "methods"))) {
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
    public function block_class_signature($context, array $blocks = [])
    {
        // line 48
        if (( !$this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "interface", []) && $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "abstract", []))) {
            echo "abstract ";
        }
        // line 49
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "categoryName", []), "html", null, true);
        echo "
    <strong>";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "shortname", []), "html", null, true);
        echo "</strong>";
        // line 51
        if ($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "parent", [])) {
            // line 52
            echo "        extends ";
            echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->getclass_link($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "parent", []));
        }
        // line 54
        if ((twig_length_filter($this->env, $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "interfaces", [])) > 0)) {
            // line 55
            echo "        implements
        ";
            // line 56
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "interfaces", []));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                // line 57
                echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->getclass_link($context["interface"]);
                // line 58
                if ( !$this->getAttribute($context["loop"], "last", [])) {
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
    public function block_method_signature($context, array $blocks = [])
    {
        // line 64
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "final", [])) {
            echo "final";
        }
        // line 65
        echo "    ";
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "abstract", [])) {
            echo "abstract";
        }
        // line 66
        echo "    ";
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "static", [])) {
            echo "static";
        }
        // line 67
        echo "    ";
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "public", [])) {
            echo "public";
        }
        // line 68
        echo "    ";
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "protected", [])) {
            echo "protected";
        }
        // line 69
        echo "    ";
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "private", [])) {
            echo "private";
        }
        // line 70
        echo "    ";
        echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->gethint_link($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "hint", []));
        echo "
    <strong>";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "name", []), "html", null, true);
        echo "</strong>";
        $this->displayBlock("method_parameters_signature", $context, $blocks);
    }

    // line 74
    public function block_method_parameters_signature($context, array $blocks = [])
    {
        // line 75
        $context["__internal_fd21f5dc4ad7b73f32d4481b0e038fbf14ced56192af014bc9878beb411e5ea8"] = $this->loadTemplate("macros.twig", "pages/class.twig", 75)->unwrap();
        // line 76
        echo $context["__internal_fd21f5dc4ad7b73f32d4481b0e038fbf14ced56192af014bc9878beb411e5ea8"]->getmethod_parameters_signature(($context["method"] ?? $this->getContext($context, "method")));
    }

    // line 79
    public function block_parameters($context, array $blocks = [])
    {
        // line 80
        echo "    <table>
        ";
        // line 81
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "parameters", []));
        foreach ($context['_seq'] as $context["_key"] => $context["parameter"]) {
            // line 82
            echo "            <tr>
                <td>";
            // line 83
            if ($this->getAttribute($context["parameter"], "hint", [])) {
                echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->gethint_link($this->getAttribute($context["parameter"], "hint", []));
            }
            echo "</td>
                <td>\$";
            // line 84
            echo twig_escape_filter($this->env, $this->getAttribute($context["parameter"], "name", []), "html", null, true);
            echo "</td>
                <td>";
            // line 85
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["parameter"], "shortdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
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
    public function block_return($context, array $blocks = [])
    {
        // line 92
        echo "    <table>
        <tr>
            <td>";
        // line 94
        echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->gethint_link($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "hint", []));
        echo "</td>
            <td>";
        // line 95
        echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "hintDesc", []), ($context["class"] ?? $this->getContext($context, "class")));
        echo "</td>
        </tr>
    </table>
";
    }

    // line 100
    public function block_exceptions($context, array $blocks = [])
    {
        // line 101
        echo "    <table>
        ";
        // line 102
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "exceptions", []));
        foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
            // line 103
            echo "            <tr>
                <td>";
            // line 104
            echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->getclass_link($this->getAttribute($context["exception"], 0, [], "array"));
            echo "</td>
                <td>";
            // line 105
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["exception"], 1, [], "array"), ($context["class"] ?? $this->getContext($context, "class")));
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
    public function block_see($context, array $blocks = [])
    {
        // line 112
        echo "    <table>
        ";
        // line 113
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "tags", [0 => "see"], "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
            // line 114
            echo "            <tr>
                <td>";
            // line 115
            echo twig_escape_filter($this->env, $this->getAttribute($context["tag"], 0, [], "array"), "html", null, true);
            echo "</td>
                <td>";
            // line 116
            echo twig_escape_filter($this->env, twig_join_filter(twig_slice($this->env, $context["tag"], 1, null), " "), "html", null, true);
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
    public function block_constants($context, array $blocks = [])
    {
        // line 123
        echo "    <table>
        ";
        // line 124
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["constants"] ?? $this->getContext($context, "constants")));
        foreach ($context['_seq'] as $context["_key"] => $context["constant"]) {
            // line 125
            echo "            <tr>
                <td>";
            // line 126
            echo twig_escape_filter($this->env, $this->getAttribute($context["constant"], "name", []), "html", null, true);
            echo "</td>
                <td class=\"last\">
                    <p><em>";
            // line 128
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["constant"], "shortdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
            echo "</em></p>
                    <p>";
            // line 129
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["constant"], "longdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
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
    public function block_properties($context, array $blocks = [])
    {
        // line 137
        echo "    <table>
        ";
        // line 138
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["properties"] ?? $this->getContext($context, "properties")));
        foreach ($context['_seq'] as $context["_key"] => $context["property"]) {
            // line 139
            echo "            <tr>
                <td class=\"type\" id=\"property_";
            // line 140
            echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
            echo "\">
                    ";
            // line 141
            if ($this->getAttribute($context["property"], "static", [])) {
                echo "static";
            }
            // line 142
            echo "                    ";
            if ($this->getAttribute($context["property"], "protected", [])) {
                echo "protected";
            }
            // line 143
            echo "                    ";
            echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->gethint_link($this->getAttribute($context["property"], "hint", []));
            echo "
                </td>
                <td>\$";
            // line 145
            echo twig_escape_filter($this->env, $this->getAttribute($context["property"], "name", []), "html", null, true);
            echo "</td>
                <td class=\"last\">";
            // line 146
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["property"], "shortdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
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
    public function block_methods($context, array $blocks = [])
    {
        // line 153
        echo "    <table>
        ";
        // line 154
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["methods"] ?? $this->getContext($context, "methods")));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
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
            if ($this->getAttribute($context["method"], "static", [])) {
                echo "static&nbsp;";
            }
            echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->gethint_link($this->getAttribute($context["method"], "hint", []));
            echo "
                </td>
                <td class=\"last\">
                    <a href=\"#method_";
            // line 160
            echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["method"], "name", []), "html", null, true);
            echo "</a>";
            $this->displayBlock("method_parameters_signature", $context, $blocks);
            echo "
                    <p>";
            // line 161
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["method"], "shortdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
            echo "</p>
                </td>
                <td>";
            // line 164
            if ( !($this->getAttribute($context["method"], "class", []) === ($context["class"] ?? $this->getContext($context, "class")))) {
                // line 165
                echo "<small>from&nbsp;";
                echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->getmethod_link($context["method"], [], false, true);
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
    public function block_methods_details($context, array $blocks = [])
    {
        // line 174
        echo "    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["methods"] ?? $this->getContext($context, "methods")));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
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
    public function block_method($context, array $blocks = [])
    {
        // line 180
        echo "    <h3 id=\"method_";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "name", []), "html", null, true);
        echo "\">
        <div class=\"location\">";
        // line 181
        if ( !($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "class", []) === ($context["class"] ?? $this->getContext($context, "class")))) {
            echo "in ";
            echo $context["__internal_995ed0c03b4f263972f95adaa69588992a72ba7f906d604e6eef2421b2cfd2ae"]->getmethod_link(($context["method"] ?? $this->getContext($context, "method")), [], false, true);
            echo " ";
        }
        echo "at line ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "line", []), "html", null, true);
        echo "</div>
        <code>";
        // line 182
        $this->displayBlock("method_signature", $context, $blocks);
        echo "</code>
    </h3>
    <div class=\"details\">
        <p>";
        // line 185
        echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "shortdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
        echo "</p>
        <p>";
        // line 186
        echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "longdesc", []), ($context["class"] ?? $this->getContext($context, "class")));
        echo "</p>
        <div class=\"tags\">
            ";
        // line 188
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "parameters", [])) {
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
        if (($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "hintDesc", []) || $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "hint", []))) {
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
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "exceptions", [])) {
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
        if ($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "tags", [0 => "see"], "method")) {
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
        return array (  687 => 211,  682 => 209,  678 => 207,  676 => 206,  673 => 205,  668 => 203,  664 => 201,  662 => 200,  659 => 199,  654 => 197,  650 => 195,  648 => 194,  645 => 193,  640 => 191,  636 => 189,  634 => 188,  629 => 186,  625 => 185,  619 => 182,  609 => 181,  604 => 180,  601 => 179,  582 => 175,  564 => 174,  561 => 173,  556 => 170,  540 => 167,  535 => 165,  533 => 164,  528 => 161,  520 => 160,  511 => 157,  507 => 155,  490 => 154,  487 => 153,  484 => 152,  479 => 149,  470 => 146,  466 => 145,  460 => 143,  455 => 142,  451 => 141,  447 => 140,  444 => 139,  440 => 138,  437 => 137,  434 => 136,  429 => 133,  419 => 129,  415 => 128,  410 => 126,  407 => 125,  403 => 124,  400 => 123,  397 => 122,  392 => 119,  383 => 116,  379 => 115,  376 => 114,  372 => 113,  369 => 112,  366 => 111,  361 => 108,  352 => 105,  348 => 104,  345 => 103,  341 => 102,  338 => 101,  335 => 100,  327 => 95,  323 => 94,  319 => 92,  316 => 91,  311 => 88,  302 => 85,  298 => 84,  292 => 83,  289 => 82,  285 => 81,  282 => 80,  279 => 79,  275 => 76,  273 => 75,  270 => 74,  264 => 71,  259 => 70,  254 => 69,  249 => 68,  244 => 67,  239 => 66,  234 => 65,  230 => 64,  227 => 63,  208 => 58,  206 => 57,  189 => 56,  186 => 55,  184 => 54,  180 => 52,  178 => 51,  175 => 50,  170 => 49,  166 => 48,  163 => 47,  156 => 43,  149 => 39,  145 => 37,  143 => 36,  140 => 35,  135 => 33,  131 => 31,  129 => 30,  126 => 29,  121 => 27,  117 => 25,  115 => 24,  112 => 23,  106 => 20,  102 => 19,  99 => 18,  97 => 17,  91 => 15,  88 => 14,  78 => 11,  73 => 10,  70 => 9,  64 => 7,  56 => 5,  52 => 1,  50 => 3,  44 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{% extends page_layout %}

{% from \"macros.twig\" import namespace_link, class_link, method_link, hint_link %}

{% block title %}{{ class.name }} | {{ parent() }}{% endblock %}

{% block body_class 'class' %}

{% block content_header %}
    <div class=\"type\">{{ class.categoryName|capitalize }}</div>
    <h1>{{ namespace_link(class.namespace) }}{% if class.namespace %}\\{% endif %}{{ class.shortname }}</h1>
{% endblock %}

{% block content %}
    <p>{{ block('class_signature') }}</p>

    {% if class.shortdesc or class.longdesc %}
        <div class=\"description\">
            <p>{{ class.shortdesc|desc(class) }}</p>
            <p>{{ class.longdesc|desc(class) }}</p>
        </div>
    {% endif %}

    {% if constants %}
        <h2>Constants</h2>

        {{ block('constants') }}
    {% endif %}

    {% if properties %}
        <h2>Properties</h2>

        {{ block('properties') }}
    {% endif %}

    {% if methods %}
        <h2>Methods</h2>

        {{ block('methods') }}

        <h2>Details</h2>

        {{ block('methods_details') }}
    {% endif %}
{% endblock %}

{% block class_signature -%}
    {% if not class.interface and class.abstract %}abstract {% endif %}
    {{ class.categoryName }}
    <strong>{{ class.shortname }}</strong>
    {%- if class.parent %}
        extends {{ class_link(class.parent) }}
    {%- endif %}
    {%- if class.interfaces|length > 0 %}
        implements
        {% for interface in class.interfaces %}
            {{- class_link(interface) }}
            {%- if not loop.last %}, {% endif %}
        {%- endfor %}
    {%- endif %}
{% endblock %}

{% block method_signature -%}
    {% if method.final %}final{% endif %}
    {% if method.abstract %}abstract{% endif %}
    {% if method.static %}static{% endif %}
    {% if method.public %}public{% endif %}
    {% if method.protected %}protected{% endif %}
    {% if method.private %}private{% endif %}
    {{ hint_link(method.hint) }}
    <strong>{{ method.name }}</strong>{{ block('method_parameters_signature') }}
{%- endblock %}

{% block method_parameters_signature -%}
    {%- from \"macros.twig\" import method_parameters_signature -%}
    {{ method_parameters_signature(method) }}
{%- endblock %}

{% block parameters %}
    <table>
        {% for parameter in method.parameters %}
            <tr>
                <td>{% if parameter.hint %}{{ hint_link(parameter.hint) }}{% endif %}</td>
                <td>\${{ parameter.name }}</td>
                <td>{{ parameter.shortdesc|desc(class) }}</td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}

{% block return %}
    <table>
        <tr>
            <td>{{ hint_link(method.hint) }}</td>
            <td>{{ method.hintDesc|desc(class) }}</td>
        </tr>
    </table>
{% endblock %}

{% block exceptions %}
    <table>
        {% for exception in method.exceptions %}
            <tr>
                <td>{{ class_link(exception[0]) }}</td>
                <td>{{ exception[1]|desc(class) }}</td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}

{% block see %}
    <table>
        {% for tag in method.tags('see') %}
            <tr>
                <td>{{ tag[0] }}</td>
                <td>{{ tag[1:]|join(' ') }}</td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}

{% block constants %}
    <table>
        {% for constant in constants %}
            <tr>
                <td>{{ constant.name }}</td>
                <td class=\"last\">
                    <p><em>{{ constant.shortdesc|desc(class) }}</em></p>
                    <p>{{ constant.longdesc|desc(class) }}</p>
                </td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}

{% block properties %}
    <table>
        {% for property in properties %}
            <tr>
                <td class=\"type\" id=\"property_{{ property.name }}\">
                    {% if property.static %}static{% endif %}
                    {% if property.protected %}protected{% endif %}
                    {{ hint_link(property.hint) }}
                </td>
                <td>\${{ property.name }}</td>
                <td class=\"last\">{{ property.shortdesc|desc(class) }}</td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}

{% block methods %}
    <table>
        {% for method in methods %}
            <tr>
                <td class=\"type\">
                    {% if method.static %}static&nbsp;{% endif %}{{ hint_link(method.hint) }}
                </td>
                <td class=\"last\">
                    <a href=\"#method_{{ method.name }}\">{{ method.name }}</a>{{ block('method_parameters_signature') }}
                    <p>{{ method.shortdesc|desc(class) }}</p>
                </td>
                <td>
                    {%- if method.class is not sameas(class) -%}
                        <small>from&nbsp;{{ method_link(method, {}, false, true) }}</small>
                    {%- endif -%}
                </td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}

{% block methods_details %}
    {% for method in methods %}
        {{ block('method') }}
    {% endfor %}
{% endblock %}

{% block method %}
    <h3 id=\"method_{{ method.name }}\">
        <div class=\"location\">{% if method.class is not sameas(class) %}in {{ method_link(method, {}, false, true) }} {% endif %}at line {{ method.line }}</div>
        <code>{{ block('method_signature') }}</code>
    </h3>
    <div class=\"details\">
        <p>{{ method.shortdesc|desc(class) }}</p>
        <p>{{ method.longdesc|desc(class) }}</p>
        <div class=\"tags\">
            {% if method.parameters %}
                <h4>Parameters</h4>

                {{ block('parameters') }}
            {% endif %}

            {% if method.hintDesc or method.hint %}
                <h4>Return Value</h4>

                {{ block('return') }}
            {% endif %}

            {% if method.exceptions %}
                <h4>Exceptions</h4>

                {{ block('exceptions') }}
            {% endif %}

            {% if method.tags('see') %}
                <h4>See also</h4>

                {{ block('see') }}
            {% endif %}
        </div>
    </div>
{% endblock %}
", "pages/class.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/pages/class.twig");
    }
}
