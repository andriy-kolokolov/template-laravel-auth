<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Project\ProjectProgrammingLanguage;
use App\Models\Project\ProjectFramework;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectsController extends Controller
{
    private array $validations = [
        'title' => 'required|string|min:5|max:50',
        'programming_languages' => 'required|string|max:500',
        'frameworks' => 'nullable|max:500',
        'description' => 'nullable',
        'project_url' => 'required|url|max:600',
    ];

    private array $validation_messages = [
        'required'  => 'The :attribute field is required',
        'min'       => 'The :attribute field must be at least :min characters',
        'max'       => 'The :attribute field cannot exceed :max characters',
        'url'       => 'The field must be a valid URL',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::paginate(5);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate($this->validations, $this->validation_messages);
        // Create a new project instance and fill it with the validated data

        $project = new Project();
        $project->title = $validatedData['title'];
        $project->description = $validatedData['description'];
        $project->project_url = $validatedData['project_url'];
        $project->save();

        // Process programming languages
        $programmingLanguages = preg_split('/[\s,]+/', $validatedData['programming_languages']);
        foreach ($programmingLanguages as $language) {
            $projectLanguage = new ProjectProgrammingLanguage();
            $projectLanguage->project_id = $project->id;
            $projectLanguage->programming_language = trim($language);
            $projectLanguage->save();
        }

        // Process frameworks if provided
        if (!empty($validatedData['frameworks'])) {
            $frameworks = preg_split('/[\s,]+/', $validatedData['frameworks']);
            foreach ($frameworks as $framework) {
                $projectFramework = new ProjectFramework();
                $projectFramework->project_id = $project->id;
                $projectFramework->framework = trim($framework);
                $projectFramework->save();
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate($this->validations, $this->validation_messages);

        // Find the project by its ID
        $project = Project::findOrFail($id);
        $project->title = $validatedData['title'];
        $project->description = $validatedData['description'];
        $project->project_url = $validatedData['project_url'];
        $project->update();

        // Process programming languages
        $programmingLanguages = preg_split('/[\s,]+/', $validatedData['programming_languages']);
        ProjectProgrammingLanguage::where('project_id', $project->id)->delete(); // Remove existing programming languages
        foreach ($programmingLanguages as $language) {
            $projectLanguage = new ProjectProgrammingLanguage();
            $projectLanguage->project_id = $project->id;
            $projectLanguage->programming_language = trim($language);
            $projectLanguage->save();
        }

        // Process frameworks if provided
        if (!empty($validatedData['frameworks'])) {
            $frameworks = preg_split('/[\s,]+/', $validatedData['frameworks']);
            ProjectFramework::where('project_id', $project->id)->delete(); // Remove existing frameworks
            foreach ($frameworks as $framework) {
                $projectFramework = new ProjectFramework();
                $projectFramework->project_id = $project->id;
                $projectFramework->framework = trim($framework);
                $projectFramework->save();
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return to_route('admin.projects.index')->with('delete_success', $project);
    }
}
