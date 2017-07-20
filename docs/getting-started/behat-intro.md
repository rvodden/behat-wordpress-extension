# Behat

[Behat](http://behat.org) is a test framework for Behavior Driven Development (BDD) in [PHP](https://secure.php.net). <abbr title="Behavior Driven Development">BDD</abbr> is a methodology for developing software through continuous example-based communication between developers and a business.

This communication happens in a form that both the business and developers can clearly understand: examples. The examples are structured around a "Context, Event, Outcome" pattern.


## Context, Event, Outcome

In Behat, tests are organised into Scenarios, and multiple Scenarios are grouped into Features. Feature files start with the story of the business feature being tested (one per file), followed by at least one Scenario.

Each Scenario consists of a list of Steps, which must start with one of the following keywords: `Given`, `When`, `Then`, `But`, `And`.

A Scenario always follows the same basic format:

```gherkin
Scenario: Some description of the scenario
    Given some context
    When some event
    Then outcome
```

Each part of the Scenario -- the context, the event, and the
outcome -- can be extended by using the `And` or `But` keywords:

```gherkin
Scenario: Some description of the scenario
    Given some context
        And more context
    When some event
        And second event occurs
    Then outcome
        And another outcome
    But another outcome
```

!!! tip
    There is no difference between the `Then`, `And`, and `But` keywords. Use them appropriately to write Scenarios that are natural and readable.


## Features

Imagine that we are building a new e-commerce website. One of the key features of any online shop is the ability to buy products, but before buying anything, customers need to be able to tell the shop which products they want to buy. We need a shopping basket.

With this, we can create our first user story:

```gherkin
Feature: Shopping basket
    In order to buy products
    As a customer
    I need to be able to put interesting products into a basket
```

Before we start development, we must have a real conversation with our business stakeholders; they might say that they want customers to not only see the combined price of the products in the basket, but also the price reflecting the tax and the delivery cost (which depends on the total price of the products):

```gherkin
Feature: Shopping basket
    In order to buy products
    As a customer
    I need to be able to put interesting products into a basket

    Rules:
    - Tax is 20%
    - Delivery for basket under £10 is £3
    - Delivery for basket over £10 is £2
```

!!! tip
    Features are a simple description of a user story.

    The format is always the same: the title of the feature, followed by three lines that describe the benefit, the role, and the feature itself. On subsequent lines, you can add any amount of additional description (such as the rules section, in this example).

In isolation, each rule by itself is understandable, but there is ambiguous complexity when we try to describe the feature in terms of *rules*. For example, what does it mean to add tax? What happens when we have two products, one of which is less than £10, and another one that is more?

To resolve this, we must have another conversation with our business stakeholders. This will often take the form of actual examples of a customer adding products to the basket. After some back-and-forth, we agree upon a list of behaviour examples.

In <abbr title="Behavior Driven Development">BDD</abbr>, these are called *Scenarios*.


## Scenarios

After conversation with our business stakeholders, we came up with the following:

```gherkin
Feature: Shopping basket
    In order to buy products
    As a customer
    I need to be able to put interesting products into a basket

    Rules:
    - Tax is 20%
    - Delivery for basket under £10 is £3
    - Delivery for basket over £10 is £2

    Scenario: Buying a single product under £10
        Given there is a "self-sealing stem bolt", which costs £5
        When I add the "self-sealing stem bolt" to the basket
        Then I should have 1 product in the basket
            And the overall basket price should be £9

    Scenario: Buying a single product over £10
        Given there is "yamok sauce", which costs £15
        When I add the "yamok sauce" to the basket
        Then I should have 1 product in the basket
            And the overall basket price should be £20

    Scenario: Buying two products over £10
        Given there is "yamok sauce", which costs £10
            And there is a "self-sealing stem bolt", which costs £5
        When I add the "yamok sauce" to the basket
            And I add the "self-sealing stem bolt" to the basket
        Then I should have 2 products in the basket
            And the overall basket price should be £20
```

!!! important
    Scenarios in Feature files should focus on the *what*, rather than the *how*.

    Each Scenario should be concise and to the point, so that the reader can quickly grasp the intent of the test without having to read a lot of irrelevant steps.

The totality of the above represents our business' shared understanding of our project, written in a structured format. It is based on the clear and constructive conversation we had together with our business stakeholders. This, in essence, is what <abbr title="Behavior Driven Development">BDD</abbr> is.


## Contexts

Behat allows us to automate behaviour checks by comparing the desired behaviour against a system's actual behavior. In other words, for our e-commerce website, Behat will compare our Scenarios against the actual website. Before it can do this, we need to teach Behat how to understand our structured language; otherwise, how is it meant to understand what we mean by "Then I should have 2 products in the basket"?.

FeatureContext is your test environment. It is an object through which you will describe how you would test your application against your features. It was generated by the --init command and now looks like this:

Finally, we got to the automation part. How does Behat know what to do
when it sees ``Given there is a "Sith Lord Lightsaber", which costs £5``? You
tell it. You write PHP code inside your context class (``FeatureContext``
in our case) and tell Behat that this code represents a specific scenario step
(via an annotation with a pattern):

```php

    /**
     * @Given there is a(n) :arg1, which costs £:arg2
     */
    public function thereIsAWhichCostsPs($arg1, $arg2)
    {
        throw new PendingException();
    }
```

``@Given there is a(n) :arg1, which costs £:arg2`` above the method tells Behat
that this particular method should be executed whenever Behat sees step that
looks like ``... there is a ..., which costs £...``. This pattern will match
any of the following steps:

```gherkin

    Given there is a "Sith Lord Lightsaber", which costs £5
    When there is a "Sith Lord Lightsaber", which costs £10
    Then there is an 'Anakin Lightsaber', which costs £10
    And there is a Lightsaber, which costs £2
    But there is a Lightsaber, which costs £25
```

Not only that, but Behat will capture tokens (words starting with ``:``, e.g.
``:arg1``) from the step and pass their value to the method as arguments:

```php

    // Given there is a "Sith Lord Lightsaber", which costs £5
    $context->thereIsAWhichCostsPs('Sith Lord Lightsaber', '5');

    // Then there is a 'Jedi Lightsaber', which costs £10
    $context->thereIsAWhichCostsPs('Jedi Lightsaber', '10');

    // But there is a Lightsaber, which costs £25
    $context->thereIsAWhichCostsPs('Lightsaber', '25');
```

```note
    If you need to define more complex matching algorithms, you can also use regular
    expressions:
```

## TDD: you could do this.

