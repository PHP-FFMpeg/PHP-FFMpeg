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

/* macros.twig */
class __TwigTemplate_dbf71cb3c241cf7dc1c3f31125835fee1c0d773624d8e53ee83c2814e7a61b60 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 4
        echo "
";
        // line 8
        echo "
";
        // line 18
        echo "
";
        // line 24
        echo "
";
        // line 30
        echo "
";
        // line 44
        echo "
";
        // line 48
        echo "
";
    }

    // line 1
    public function getattributes($__attributes__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "attributes" => $__attributes__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 2
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["attributes"] ?? $this->getContext($context, "attributes")));
            foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                echo " ";
                echo twig_escape_filter($this->env, $context["key"], "html", null, true);
                echo "=\"";
                echo twig_escape_filter($this->env, $context["value"], "html", null, true);
                echo "\"";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 5
    public function getnamespace_link($__namespace__ = null, $__attributes__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "namespace" => $__namespace__,
            "attributes" => $__attributes__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 6
            echo "<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForNamespace($context, ($context["namespace"] ?? $this->getContext($context, "namespace"))), "html", null, true);
            echo "\"";
            echo $this->getAttribute($this, "attributes", [0 => ($context["attributes"] ?? $this->getContext($context, "attributes"))], "method");
            echo ">";
            echo twig_escape_filter($this->env, ($context["namespace"] ?? $this->getContext($context, "namespace")), "html", null, true);
            echo "</a>";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 9
    public function getclass_link($__class__ = null, $__attributes__ = null, $__absolute__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "class" => $__class__,
            "attributes" => $__attributes__,
            "absolute" => $__absolute__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 10
            if ($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "projectclass", [])) {
                // line 11
                echo "<a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForClass($context, ($context["class"] ?? $this->getContext($context, "class"))), "html", null, true);
                echo "\"";
                echo $this->getAttribute($this, "attributes", [0 => ($context["attributes"] ?? $this->getContext($context, "attributes"))], "method");
                echo ">";
            } elseif ($this->getAttribute(            // line 12
($context["class"] ?? $this->getContext($context, "class")), "phpclass", [])) {
                // line 13
                echo "<a href=\"http://php.net/";
                echo twig_escape_filter($this->env, ($context["class"] ?? $this->getContext($context, "class")), "html", null, true);
                echo "\"";
                echo $this->getAttribute($this, "attributes", [0 => ($context["attributes"] ?? $this->getContext($context, "attributes"))], "method");
                echo ">";
            }
            // line 15
            echo $this->getAttribute($this, "abbr_class", [0 => ($context["class"] ?? $this->getContext($context, "class")), 1 => (((isset($context["absolute"]) || array_key_exists("absolute", $context))) ? (_twig_default_filter(($context["absolute"] ?? $this->getContext($context, "absolute")), false)) : (false))], "method");
            // line 16
            if (($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "projectclass", []) || $this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "phpclass", []))) {
                echo "</a>";
            }
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 19
    public function getmethod_link($__method__ = null, $__attributes__ = null, $__absolute__ = null, $__classonly__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "method" => $__method__,
            "attributes" => $__attributes__,
            "absolute" => $__absolute__,
            "classonly" => $__classonly__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 20
            echo "<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForMethod($context, ($context["method"] ?? $this->getContext($context, "method"))), "html", null, true);
            echo "\"";
            echo $this->getAttribute($this, "attributes", [0 => ($context["attributes"] ?? $this->getContext($context, "attributes"))], "method");
            echo ">";
            // line 21
            echo $this->getAttribute($this, "abbr_class", [0 => $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "class", [])], "method");
            if ( !(((isset($context["classonly"]) || array_key_exists("classonly", $context))) ? (_twig_default_filter(($context["classonly"] ?? $this->getContext($context, "classonly")), false)) : (false))) {
                echo "::";
                echo twig_escape_filter($this->env, $this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "name", []), "html", null, true);
            }
            // line 22
            echo "</a>";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 25
    public function getproperty_link($__property__ = null, $__attributes__ = null, $__absolute__ = null, $__classonly__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "property" => $__property__,
            "attributes" => $__attributes__,
            "absolute" => $__absolute__,
            "classonly" => $__classonly__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 26
            echo "<a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForProperty($context, ($context["property"] ?? $this->getContext($context, "property"))), "html", null, true);
            echo "\"";
            echo $this->getAttribute($this, "attributes", [0 => ($context["attributes"] ?? $this->getContext($context, "attributes"))], "method");
            echo ">";
            // line 27
            echo $this->getAttribute($this, "abbr_class", [0 => $this->getAttribute(($context["property"] ?? $this->getContext($context, "property")), "class", [])], "method");
            if ( !(((isset($context["classonly"]) || array_key_exists("classonly", $context))) ? (_twig_default_filter(($context["classonly"] ?? $this->getContext($context, "classonly")), true)) : (true))) {
                echo "#";
                echo twig_escape_filter($this->env, $this->getAttribute(($context["property"] ?? $this->getContext($context, "property")), "name", []), "html", null, true);
            }
            // line 28
            echo "</a>";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 31
    public function gethint_link($__hints__ = null, $__attributes__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "hints" => $__hints__,
            "attributes" => $__attributes__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 32
            if (($context["hints"] ?? $this->getContext($context, "hints"))) {
                // line 33
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["hints"] ?? $this->getContext($context, "hints")));
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
                foreach ($context['_seq'] as $context["_key"] => $context["hint"]) {
                    // line 34
                    if ($this->getAttribute($context["hint"], "class", [])) {
                        // line 35
                        echo $this->getAttribute($this, "class_link", [0 => $this->getAttribute($context["hint"], "name", [])], "method");
                    } elseif ($this->getAttribute(                    // line 36
$context["hint"], "name", [])) {
                        // line 37
                        echo $this->env->getExtension('Sami\Renderer\TwigExtension')->abbrClass($this->getAttribute($context["hint"], "name", []));
                    }
                    // line 39
                    if ($this->getAttribute($context["hint"], "array", [])) {
                        echo "[]";
                    }
                    // line 40
                    if ( !$this->getAttribute($context["loop"], "last", [])) {
                        echo "|";
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
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['hint'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
            }
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 45
    public function getabbr_class($__class__ = null, $__absolute__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "class" => $__class__,
            "absolute" => $__absolute__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 46
            echo "<abbr title=\"";
            echo twig_escape_filter($this->env, ($context["class"] ?? $this->getContext($context, "class")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (((((isset($context["absolute"]) || array_key_exists("absolute", $context))) ? (_twig_default_filter(($context["absolute"] ?? $this->getContext($context, "absolute")), false)) : (false))) ? (($context["class"] ?? $this->getContext($context, "class"))) : ($this->getAttribute(($context["class"] ?? $this->getContext($context, "class")), "shortname", []))), "html", null, true);
            echo "</abbr>";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    // line 49
    public function getmethod_parameters_signature($__method__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "method" => $__method__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 50
            $context["__internal_3f7565190ce095cf15ef14d89e4780810bd6c7dab9662846e06081a61be1f8f3"] = $this->loadTemplate("macros.twig", "macros.twig", 50)->unwrap();
            // line 51
            echo "(";
            // line 52
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["method"] ?? $this->getContext($context, "method")), "parameters", []));
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
            foreach ($context['_seq'] as $context["_key"] => $context["parameter"]) {
                // line 53
                if ($this->getAttribute($context["parameter"], "hashint", [])) {
                    echo $context["__internal_3f7565190ce095cf15ef14d89e4780810bd6c7dab9662846e06081a61be1f8f3"]->gethint_link($this->getAttribute($context["parameter"], "hint", []));
                    echo " ";
                }
                // line 54
                echo "\$";
                echo twig_escape_filter($this->env, $this->getAttribute($context["parameter"], "name", []), "html", null, true);
                // line 55
                if ($this->getAttribute($context["parameter"], "default", [])) {
                    echo " = ";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["parameter"], "default", []), "html", null, true);
                }
                // line 56
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parameter'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 58
            echo ")";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "macros.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  428 => 58,  412 => 56,  407 => 55,  404 => 54,  399 => 53,  382 => 52,  380 => 51,  378 => 50,  366 => 49,  347 => 46,  334 => 45,  304 => 40,  300 => 39,  297 => 37,  295 => 36,  293 => 35,  291 => 34,  274 => 33,  272 => 32,  259 => 31,  244 => 28,  238 => 27,  232 => 26,  217 => 25,  202 => 22,  196 => 21,  190 => 20,  175 => 19,  158 => 16,  156 => 15,  149 => 13,  147 => 12,  141 => 11,  139 => 10,  125 => 9,  104 => 6,  91 => 5,  65 => 2,  53 => 1,  48 => 48,  45 => 44,  42 => 30,  39 => 24,  36 => 18,  33 => 8,  30 => 4,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{% macro attributes(attributes) %}
    {%- for key, value in attributes %} {{ key }}=\"{{ value }}\"{% endfor -%}
{% endmacro %}

{% macro namespace_link(namespace, attributes) -%}
    <a href=\"{{ namespace_path(namespace) }}\"{{ _self.attributes(attributes) }}>{{ namespace }}</a>
{%- endmacro %}

{% macro class_link(class, attributes, absolute) -%}
    {%- if class.projectclass -%}
        <a href=\"{{ class_path(class) }}\"{{ _self.attributes(attributes) }}>
    {%- elseif class.phpclass -%}
        <a href=\"http://php.net/{{ class }}\"{{ _self.attributes(attributes) }}>
    {%- endif %}
        {{- _self.abbr_class(class, absolute|default(false)) }}
    {%- if class.projectclass or class.phpclass %}</a>{% endif %}
{%- endmacro %}

{% macro method_link(method, attributes, absolute, classonly) -%}
    <a href=\"{{ method_path(method) }}\"{{ _self.attributes(attributes) }}>
        {{- _self.abbr_class(method.class) }}{% if not classonly|default(false) %}::{{ method.name }}{% endif -%}
    </a>
{%- endmacro %}

{% macro property_link(property, attributes, absolute, classonly) -%}
    <a href=\"{{ property_path(property) }}\"{{ _self.attributes(attributes) }}>
        {{- _self.abbr_class(property.class) }}{% if not classonly|default(true) %}#{{ property.name }}{% endif -%}
    </a>
{%- endmacro %}

{% macro hint_link(hints, attributes) -%}
    {% if hints %}
        {%- for hint in hints %}
            {%- if hint.class %}
                {{- _self.class_link(hint.name) }}
            {%- elseif hint.name %}
                {{- abbr_class(hint.name) }}
            {%- endif %}
            {%- if hint.array %}[]{% endif %}
            {%- if not loop.last %}|{% endif %}
        {%- endfor %}
    {%- endif %}
{%- endmacro %}

{% macro abbr_class(class, absolute) -%}
    <abbr title=\"{{ class }}\">{{ absolute|default(false) ? class : class.shortname }}</abbr>
{%- endmacro %}

{% macro method_parameters_signature(method) -%}
    {%- from \"macros.twig\" import hint_link -%}
    (
        {%- for parameter in method.parameters %}
            {%- if parameter.hashint %}{{ hint_link(parameter.hint) }} {% endif -%}
            \${{ parameter.name }}
            {%- if parameter.default %} = {{ parameter.default }}{% endif %}
            {%- if not loop.last %}, {% endif %}
        {%- endfor -%}
    )
{%- endmacro %}
", "macros.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/macros.twig");
    }
}
