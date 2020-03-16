import { Component, OnInit } from "@angular/core";
import { BlogService } from "../blog.service";
import { ActivatedRoute } from "@angular/router";

@Component({
  selector: "app-blog-view",
  templateUrl: "./blog-view.component.html",
  styleUrls: ["./blog-view.component.scss"]
})
export class BlogViewComponent implements OnInit {
  slug: string;
  blog: any = {};

  dataLoaded = false;

  constructor(private route: ActivatedRoute, private blogService: BlogService) {
    const defaultImage = "/assets/images/images/blog/default.png";

    this.route.params.subscribe(params => {
      this.slug = params.slug;

      this.blogService.findBySlug(this.slug).subscribe((result: any) => {
        this.dataLoaded = true;
        this.blog = result.blog;

        if (!this.blog.cover_image) {
          this.blog.cover_image = defaultImage;
        }
      });
    });
  }

  ngOnInit() {}
}
