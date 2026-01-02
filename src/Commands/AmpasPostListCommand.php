<?php declare(strict_types=1);

namespace Ampas\Blog\Commands;

use Ampas\Blog\Entities\Post;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'post:list', description: 'View recent blog posts (last 20 entries)')]
class AmpasPostListCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $posts = new Post();
        $posts = $posts->orderByDesc('created_at')
            ->limit(20)
            ->get();

        if ($posts->count() == 0) {
            $io->warning('No posts found.');
            return Command::SUCCESS;
        }

        $table = [];
        foreach ($posts as $post) {
            if (! ($post instanceof Post)) {
                continue;
            }

            $table[] = [
                $post->title,
                $post->description,
                $post->slug,
                $post->created_at,
            ];
        }

        $io->table(['Title', 'Description', 'Slug', 'Created At'], $table);
        return Command::SUCCESS;
    }
}
