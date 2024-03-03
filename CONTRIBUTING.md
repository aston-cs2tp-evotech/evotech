# git guide for evotech;

This guide aims to help you with contributing towards our group project. Feel free to ask me if you have any questions

## Getting Started

1. **Fork the Repository:**
   - Click on the "Fork" button at the top right of the [GitHub repository page](https://github.com/aston-cs2tp-evotech/evotech).
   - This will create a copy of the repository under your GitHub account.

2. **Clone Your Fork:**
   - Open your terminal.
   - Run the following command, replacing `your-username` with your GitHub username:
```bash
git clone https://github.com/your-username/evotech.git
```

3. **Navigate to the Project Directory:**
```bash
cd evotech
```

4. **Add Upstream Remote:**
   - To keep your fork up-to-date with the main repository, add the upstream remote to be the main repository:
```bash
git remote add upstream https://github.com/aston-cs2tp-evotech/evotech.git
```

## Making Changes

1. **Create a Branch:**
   - Create a new branch for your feature or bug fix. Replace `feature-branch` with the feature or thing you're working on for this particular branch.
```bash
git checkout -b feature-branch
```

2. **Make Changes:**
   - Write your code, make improvements, or fix bugs.

3. **Commit Your Changes:**
   - Stage your changes:
```bash
git add .
```
   - Commit your changes with a descriptive message:
```bash
git commit -m "Your commit message here"
```

4. **Pull Latest Changes (Optional but Recommended):**
   - Before pushing your changes, pull the latest changes from the main repository:
```bash
git pull upstream main
```

5. **Resolve Conflicts (If Any):**
   - If there are conflicts, resolve them, then commit the changes.

## Submitting a Pull Request

1. **Push Your Changes:**
   - Push your changes to your forked repository:
```bash
git push origin feature-branch
```

2. **Create a Pull Request:**
   - Visit your fork on your GitHub page.
   - Click on the "New pull request" button.
   - Set the base branch to `main` and the compare branch to your feature branch.
   - Write a descriptive title and comment for your pull request.

3. **Request Review:**
   - Assign reviewers and request a review to notify others of your changes.

4. **Address Feedback (if any):**
   - Make changes based on the feedback received.

5. **Merge Pull Request:**
   - Once approved, your pull request will be merged into the main branch.

## Keeping Your Fork Updated

To keep your fork updated with the main repository:

1. **Fetch Latest Changes:**
```bash
git fetch upstream
```

2. **Merge Changes into Your Branch:**
```bash
git merge upstream/main
```

3. **Push Changes to Your Fork:**
```bash
git push origin main
```
