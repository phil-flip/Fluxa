import {z} from "zod";

export const schema = z.object({
    projectId: z.string().uuid(),
    teamId: z.string().uuid(),
    title: z.string(),
    description: z.string().optional(),
    statusId: z.string().uuid().optional(),
    milestoneId: z.string().uuid().optional(),
    cycleId: z.string().uuid().optional(),
    labelIds: z.string().array(),
    projectGroupIds: z.string().array(),
    componentIds: z.string().array(),
});
