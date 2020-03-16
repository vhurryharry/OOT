import { Component, OnInit } from "@angular/core";
import { FormBuilder, Validators } from "@angular/forms";
import { RepositoryService } from "../../services/repository.service";
import slugify from "slugify";
import * as ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { ActivatedRoute, Router } from "@angular/router";

declare var $: any;

@Component({
  selector: "admin-create-blog",
  templateUrl: "./create-blog.component.html",
  styleUrls: ["./create-blog.component.css"]
})
export class CreateBlogComponent implements OnInit {
  pageTitle = "";
  blogId: string = null;
  loading = false;
  blogCategories = [];
  coverImageSource: File = null;
  coverImage = "";

  blogForm = this.fb.group({
    id: [""],
    slug: ["", Validators.required],
    category: [""],
    coverImage: [""],
    title: ["", Validators.required],
    subtitle: ["", Validators.required],
    content: [""]
  });
  public editor = ClassicEditor;

  constructor(
    private fb: FormBuilder,
    private repository: RepositoryService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    this.route.params.subscribe(params => {
      this.blogId = params.id;
      if (params.id === "0") {
        this.blogId = null;
      }

      if (this.blogId) {
        this.pageTitle = "Edit Blog";
      } else {
        this.pageTitle = "Create New Blog";
      }
    });

    this.blogForm.get("title").valueChanges.subscribe(val => {
      if (!val) {
        return;
      }

      this.blogForm.patchValue({ slug: slugify(val, { lower: true }) });
    });
  }

  ngOnInit() {
    this.loading = true;
    this.repository.fetch("blog_category", {}).subscribe((result: any) => {
      this.blogCategories = result.items;

      if (this.blogId) {
        this.repository.find("blog", this.blogId).subscribe((res: any) => {
          this.loading = false;

          this.coverImage = res.coverImage;

          this.blogForm.patchValue(res);
        });
      }
    });
  }

  goBack() {
    this.router.navigate(["/pages"]);
  }

  onUpdateCoverImage() {
    $("#blog-cover-image").trigger("click");
  }

  onUpdateCoverImageSource($event: Event) {
    this.coverImageSource = ($event.target as any).files[0] as File;

    const reader = new FileReader();
    reader.onload = (e: any) => {
      this.coverImage = e.target.result;
    };

    // This will process our file and get it"s attributes/data
    reader.readAsDataURL(this.coverImageSource);
  }

  onSubmit() {
    this.loading = true;

    if (!this.blogId) {
      delete this.blogForm.value.id;

      const data = {
        ...this.blogForm.value,
        authorAvatar: "",
        course: this.blogId
      };

      this.repository.create("blog", data).subscribe((result: any) => {
        if (this.coverImageSource) {
          this.repository
            .uploadFile("blog", this.coverImageSource, result.id)
            .subscribe((res: any) => {
              this.loading = false;
              this.router.navigate(["/blogs"]);
            });
        } else {
          this.loading = false;
          this.router.navigate(["/blogs"]);
        }
      });
    } else {
      this.blogForm.value.coverImage = "";

      this.repository
        .update("blog", this.blogForm.value)
        .subscribe((result: any) => {
          if (this.coverImageSource) {
            this.repository
              .uploadFile("blog", this.coverImageSource, result.id)
              .subscribe((res: any) => {
                this.loading = false;
                this.router.navigate(["/blogs"]);
              });
          } else {
            this.loading = false;
            this.router.navigate(["/blogs"]);
          }
        });
    }
  }
}
