# hippiemedia/resource

## what ?

A php library to describe resources and serve different hypermedia formats based on content negotiation.


## how ?

Example integration with a symfony controller

```php
use Hippiemedia\Resource;
use Hippiemedia\Link;
use Hippiemedia\Operation;
use Hippiemedia\Field;
use Hippiemedia\Format;
use Hippiemedia\Negotiate;

final class Hypermedia
{
    private $urls;
    private $negotiate;

    public function __construct(UrlGenerator $urls, Negotiate $negotiate)
    {
        $this->negotiate = $negotiate;
        $this->urls = $urls;
    }

    public function negotiate(?string $accept): Format
    {
        return ($this->negotiate)($accept);
    }

    public function entrypoint(): Resource
    {
        return new Resource($this->urls->url('entrypoint'), [], [
            new Link(['accounts'], $this->urls->template('accounts', ['limit' => '{limit}', 'offset' => '{offset}',]), false, 'List of all accounts'),
            new Link(['account'], $this->urls->template('account'), true, 'Details of one account by id'),
            new Link(['account-by-email'], $this->urls->template('account-by-email'), true, 'Details of one account by email'),
        ], [
            new Operation('create-account', 'POST', $this->urls->template('create-account'), false, 'Create a new account', '', [
                new Field('email', 'email', true, 'ex: test@example.org'),
                new Field('initial-credits', 'number', true, 'ex: 5000'),
                new Field('with-mapping', 'text', true, 'yes'),
                new Field('PAPO-project-id', 'text', true, ''),
            ]),
            new Operation('deactive-account', 'DELETE', $this->urls->template('deactivate-account'), true, 'Deactivate an account by id'),
            new Operation('reactive-account', 'PUT', $this->urls->template('reactivate-account'), true, '(Re)activate an account by id'),
            new Operation('credit-account', 'POST', $this->urls->template('credit-account'), true, 'Add new credits to an existing account', '', [
                new Field('amount', 'number', true, 'ex: 1000'),
            ]),
        ]);
    }
}
```


 - Serialize the content from an http controller:

```php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Hypermedia;

final class EntrypointController
{
    private $hypermedia;

    public function __construct(Hypermedia $hypermedia)
    {
        $this->hypermedia = $hypermedia;
    }

    /**
     * @Route(name="entrypoint", path="/api", methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        $format = $this->hypermedia->negotiate($request->headers->get('Accept'));
        $resource = $format($this->hypermedia->entrypoint());

        return new JsonResponse($resource, 200, ['Content-Type' => $format->accepts()]);
    }
}
```
