import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { TagsComponent } from './tags.component';
import { TagListComponent } from './tag-list/tag-list.component';
import { CreateTagComponent } from './create-tag/create-tag.component';

const routes: Routes = [
  {
    path: '',
    component: TagsComponent,
    children: [
      {
        path: 'edit/:id',
        component: CreateTagComponent
      },
      {
        path: '',
        component: TagListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TagsRoutingModule {}
