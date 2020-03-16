import { Component, OnInit } from "@angular/core";
import { Router } from "@angular/router";

import { BlogService } from "../blog.service";

@Component({
  selector: "app-blog-list",
  templateUrl: "./blog-list.component.html",
  styleUrls: ["./blog-list.component.scss"]
})
export class BlogListComponent implements OnInit {
  blogs = [];
  categories = [];

  dataLoaded = false;

  constructor(private blogService: BlogService, private router: Router) {
    const defaultImage = "/assets/images/images/blog/default.png";

    this.blogService.get().subscribe((result: any) => {
      this.dataLoaded = true;

      this.blogs = result.blogs.map(blog => {
        if (!blog.cover_image) {
          blog.cover_image = defaultImage;
        }

        return blog;
      });
      this.categories = result.categories;
    });
  }

  ngOnInit() {}

  showBlog(slug: string) {
    this.router.navigateByUrl("/blog/" + slug);
  }
}
