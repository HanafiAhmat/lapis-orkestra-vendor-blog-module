<?php declare(strict_types=1);

namespace Ampas\Blog\Controllers;

use Ampas\Blog\Actions\PostUpdateAction;
use Ampas\Blog\Checkers\PostStoreChecker;
use Ampas\Blog\Checkers\PostUpdateChecker;
use Ampas\Blog\Entities\Post;
use Ampas\Blog\Entities\Category;
use Ampas\Blog\Entities\Tag;
use BitSynama\Lapis\Framework\Controllers\AbstractAdminController;
use BitSynama\Lapis\Framework\DTO\ActionResponse;
use BitSynama\Lapis\Framework\Exceptions\NotFoundException;
use BitSynama\Lapis\Framework\Exceptions\ValidationException;
use BitSynama\Lapis\Framework\Foundation\Constants;
use BitSynama\Lapis\Lapis;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class AdminPostController extends AbstractAdminController
{
    protected string $entityClass = Post::class;
    protected string $storeCheckerClass = PostStoreChecker::class;
    protected string $updateCheckerClass = PostUpdateChecker::class;
    protected string $listTemplate = 'blog.admin.post.list';
    protected string $showTemplate = 'admin.show';
    protected string $createTemplate = 'blog.admin.post.create';
    protected string $editTemplate = 'blog.admin.post.edit';

    /**
     * List records with filters and pagination.
     */
    public function list(ServerRequestInterface $request): ActionResponse
    {
        // if (! $this->isAuthorized('list')) {
        //     return;
        // }

        $queryParams = $request->getQueryParams();
        $filter = $queryParams['filter'] ?? [];
        $sort = $queryParams['sort'] ?? null;
        $page = $queryParams['page'] ?? [];

        $entityTotalCount = method_exists($this, 'newEntityInstance')
            ? $this->newEntityInstance()
            : new $this->entityClass();
        $entity = method_exists($this, 'newEntityInstance')
            ? $this->newEntityInstance()
            : new $this->entityClass();

        $availableColumns = $entity->getConnection()
                                ->getSchemaBuilder()
                                ->getColumnListing($entity->getTable());
        if (! empty($filter)) {
            foreach ($filter as $attribute => $value) {
                if (! in_array($attribute, $availableColumns)) {
                    continue;
                }

                if (substr_count((string) $value, '%') > 0) {
                    $entityTotalCount = $entityTotalCount->where($attribute, 'like', $value);
                    $entity = $entity->where($attribute, 'like', $value);
                } else {
                    $entityTotalCount = $entityTotalCount->where($attribute, $value);
                    $entity = $entity->where($attribute, $value);
                }
            }
        }

        if (! empty($sort)) {
            $attribute = ltrim((string) $sort, '-');
            if (in_array($attribute, $availableColumns)) {
                $sortOrder = (strpos((string) $sort, '-') > -1) ? 'DESC' : 'ASC';
                $entity = $entity->orderBy($attribute, $sortOrder);
            }
        }

        $page['num'] = $page['num'] ?? 1;
        if (! is_numeric($page['num'])) {
            $page['num'] = 1;
        }
        $defaultLimit = 10;
        $page['limit'] = $page['limit'] ?? $defaultLimit;
        if (! is_numeric($page['limit'])) {
            $page['limit'] = $defaultLimit;
        }
        if (! empty($page)) {
            if ($page['num'] - 1 > 0) {
                $offset = ($page['num'] - 1) * $page['limit'];
                $entity = $entity->offset($offset);
            }
            $entity = $entity->limit($page['limit']);
        }

        $totalRecords = $entityTotalCount->get()->count();
        $entities = $entity->with(['category', 'createdBy', 'updatedBy', 'tags'])->get();

        $title = 'List of blog posts';
        $data = [
            'title'   => $title,
            'records' => $entities->toArray(),
            'pagination' => [
                'total' => $totalRecords,
                'page' => $page,
                'total_pages' => (int) ceil($totalRecords / $page['limit']),
                'current_query' => $queryParams,
                'url_path' => $request->getUri()->getPath(),
            ],
        ];

        return new ActionResponse(
            status: ActionResponse::SUCCESS,
            data: $data,
            message: $title,
            template: $this->listTemplate
        );
    }

    /**
     * Show create new entity form.
     */
    public function create(ServerRequestInterface $request): ActionResponse
    {
        // if (! $this->isAuthorized('list')) {
        //     return;
        // }

        $sessionUtility = Lapis::sessionUtility();
        $csrfToken = $sessionUtility->getCsrfToken();
        $oldInputs = $sessionUtility->getFlash('old_inputs', []);
        $errors = $sessionUtility->getFlash('validation_errors', []);

        return new ActionResponse(
            status: ActionResponse::SUCCESS,
            data: [
                'csrf_token' => $csrfToken,
                'errors' => $errors,
                'old' => $oldInputs,
                'fail' => !empty($errors) ? 'Validation error' : '',     // Global form error
                'categories' => Category::all(),
                'tags' => Tag::all(), // For tag selection
            ],
            message: 'Create New Blog Post Endpoint Ready',
            template: $this->createTemplate
        );
    }

    /**
     * Show edit entity form.
     */
    public function edit(ServerRequestInterface $request, int|string $id): ActionResponse
    {
        // if (! $this->isAuthorized('list')) {
        //     return;
        // }

        $record = $this->entityClass::find($id);

        if (! $record) {
            return new ActionResponse(
                status: ActionResponse::FAIL,
                data: ['fail' => 'Blog post record not found'],
                message: 'Record not found',
                statusCode: Constants::STATUS_CODE_NOT_FOUND,
                htmlRedirect: '/admin/blog/posts'
            );
        }

        $sessionUtility = Lapis::sessionUtility();
        $csrfToken = $sessionUtility->getCsrfToken();
        $oldInputs = $sessionUtility->getFlash('old_inputs', []);
        $errors = $sessionUtility->getFlash('validation_errors', []);

        return new ActionResponse(
            status: ActionResponse::SUCCESS,
            data: [
                'csrf_token' => $csrfToken,
                'errors' => $errors,
                'old' => $oldInputs ?: $record->append(['tag_ids'])->toArray(),
                'fail' => !empty($errors) ? 'Validation error' : '',     // Global form error
                'categories' => Category::all(),
                'tags' => Tag::all(), // For tag selection
            ],
            message: 'Update Blog Post Record Endpoint Ready',
            template: $this->editTemplate
        );
    }

    /**
     * Update an entity record.
     */
    public function updateBak(ServerRequestInterface $request, int|string $id): ActionResponse
    {
        // if (! $this->isAuthorized('update')) {
        //     return;
        // }

        $sessionUtility = Lapis::sessionUtility();
        $htmlRedirect = Lapis::requestUtility()->getReferer();

        $checker = new $this->updateCheckerClass();
        $entityClass = new $this->entityClass();

        $entity = $entityClass->find($id);
        if (! $entity) {
            $errorMessage = 'Blog Post not found';
            $sessionUtility->setAlert('warning', $errorMessage);
            
            return new ActionResponse(
                status: ActionResponse::FAIL,
                data: ['fail' => $errorMessage],
                message: $errorMessage,
                statusCode: Constants::STATUS_CODE_NOT_FOUND,
                htmlRedirect: $htmlRedirect
            );
        }

        $checker->setEntity($entity);
        $inputs = $request->getParsedBody();
        if (! $checker->isValid($inputs)) {
            $sessionUtility->setFlash('old_inputs', $inputs);
            $sessionUtility->setFlash('validation_errors', $checker->getErrors());
            
            return new ActionResponse(
                status: ActionResponse::FAIL,
                data: ['error' => $checker->getErrors()],
                message: 'Failed validation',
                statusCode: Constants::STATUS_CODE_DATA_CONFLICT,
                htmlRedirect: $htmlRedirect
            );
        }

        $validInputs = $checker->getInputs();

        $columns = $entity->getConnection()
                        ->getSchemaBuilder()
                        ->getColumnListing($entity->getTable());
        foreach ($validInputs as $key => $value) {
            if (in_array($key, $columns)) {
                $entity->{$key} = $value;
            }
        }

        $user = $request->getAttribute('user');
        $entity->updated_by_type = $user->user_type;
        $entity->updated_by_id = $user->getId();
        $entity->update();

        $tagIdsToBeAdded = [];
        if (count($validInputs['tag_ids']) > 0) {
            $tagIdsToBeAdded = array_diff($validInputs['tag_ids'], $entity->tags->pluck('blog_tag_id')->toArray());
        }

        if ($entity->tags->count() > 0) {
            if (count($validInputs['tag_ids']) > 0) {
                $tagIdsToBeRemoved = [];
                $entity->tags->map(function ($postTag) use ($validInputs) {
                    if (!in_array($postTag->blog_tag_id, $validInputs['tag_ids'])) {
                        $tagIdsToBeRemoved[] = $postTag->blog_tag_id;
                    }
                });

                if (count($tagIdsToBeRemoved) > 0) {
                    $entity->tags()->detach($tagIdsToBeRemoved);
                }
            } else {
                $entity->tags()->detach();
            }
        }

        if (count($tagIdsToBeAdded) > 0) {
            $entity->tags()->attach($tagIdsToBeAdded);
        }

        if ($auditLog = Lapis::interactorRegistry()->getOrSkip('plugin.system_monitor.audit_log')) {
            $auditLog::record($this->entityClass . ' updated', [
                'stored_entity_id' => $id,
                'user_type' => $user->user_type,
                'user_id' => $user->getId(),
                'client_type' => Lapis::requestUtility()->getClientType(),
                'user_agent' => Lapis::requestUtility()->getUserAgent(),
                'ip_address' => $request->getAttribute('client-ip'),
            ]);
        }

        $successMessage = 'Blog Post updated successfully';
        $sessionUtility->setAlert('success', $successMessage);

        return new ActionResponse(
            status: ActionResponse::SUCCESS,
            data: ['record' => $entity->append(['tag_ids'])->toArray()],
            message: $successMessage,
            htmlRedirect: $htmlRedirect
        );
    }

    public function update(ServerRequestInterface $request, int|string $id): ActionResponse
    {
        // if (! $this->isAuthorized('update')) {
        //     return;
        // }

        $sessionUtility = Lapis::sessionUtility();
        $htmlRedirect = Lapis::requestUtility()->getReferer();

        try {
            $action = new PostUpdateAction($request, $id);
            $post = $action->handle();

            $successMessage = 'Blog Post updated successfully';
            $sessionUtility->setAlert('success', $successMessage);

            return new ActionResponse(
                status: ActionResponse::SUCCESS,
                data: ['record' => $entity->append(['tag_ids'])->toArray()],
                message: $successMessage,
                htmlRedirect: $htmlRedirect
            );
        } catch (NotFoundException $e) {
            $errorMessage = $e->getMessage();
            $sessionUtility->setAlert('warning', $errorMessage);
            
            return new ActionResponse(
                status: ActionResponse::FAIL,
                data: ['fail' => $errorMessage],
                message: $errorMessage,
                statusCode: $e->getCode(),
                htmlRedirect: $htmlRedirect
            );
        } catch (ValidationException $e) {
            $inputs = $request->getParsedBody();
            $sessionUtility->setFlash('old_inputs', $inputs);
            $sessionUtility->setFlash('validation_errors', $e->getErrors());
            
            return new ActionResponse(
                status: ActionResponse::FAIL,
                data: ['error' => $e->getErrors()],
                message: $e->getMessage(),
                statusCode: $e->getCode(),
                htmlRedirect: $htmlRedirect
            );
        } catch (Throwable $e) {
            // dd($e);
            // return new ActionResponse(
            //     status: ActionResponse::ERROR,
            //     data: ['error' => $e->getMessage()],
            //     message: $e->getMessage(),
            //     statusCode: $e->getCode() < 100 && $e->getCode() > 500 ? 500 : $e->getCode(),
            //     // htmlRedirect: $htmlRedirect
            // );
        }
    }
}
